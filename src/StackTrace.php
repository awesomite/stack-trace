<?php

namespace Awesomite\StackTrace;

use Awesomite\Iterators\CallbackIterator;
use Awesomite\StackTrace\Arguments\Values\DeserializedValue;
use Awesomite\StackTrace\Arguments\Values\Value;
use Awesomite\StackTrace\SourceCode\File;
use Awesomite\StackTrace\Steps\Step;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\VarDumperInterface;
use Composer\Semver\Semver;

/**
 * @internal
 */
class StackTrace implements StackTraceInterface
{
    const VERSION = '0.9.0';
    const CONSTRAINTS_VERSION = '>=0.1.0 <0.10.0';

    private $arrayStackTrace;

    private $withoutArgs = false;

    private $files = array();

    private $unserialized = false;

    /**
     * @var VarDumperInterface|null
     */
    private $varDumper;

    public function __construct(array $arrayStackTrace)
    {
        $this->arrayStackTrace = new \ArrayObject($arrayStackTrace);
    }

    public function getIterator()
    {
        $stackTrace = $this->arrayStackTrace;
        $self = $this;
        $i = -1;

        return new CallbackIterator(function () use ($stackTrace, $self, &$i) {
            if (++$i < $self->count()) {
                return new Step($self->convertStep($stackTrace[$i]));
            }

            CallbackIterator::stopIterate();
        });
    }

    public function count()
    {
        return count($this->arrayStackTrace);
    }

    public function serialize()
    {
        if ($this->unserialized) {
            $steps = $this->arrayStackTrace;
        } else {
            $steps = array();
            $maxThreshold = Constants::MAX_LINE_THRESHOLD;
            foreach ($this->arrayStackTrace as $step) {
                $steps[] = $this->convertStep($step, true);
                $fileName = isset($step['file']) ? $step['file'] : false;
                if ($fileName && is_file($fileName)) {
                    $file = isset($this->files[$fileName])
                        ? $this->files[$fileName]
                        : new File($fileName);
                    if (isset($step['line'])) {
                        $file->addThreshold($step['line'] - $maxThreshold, $step['line'] + $maxThreshold);
                    }
                    $this->files[$fileName] = $file;
                }
            }
        }

        return serialize(array(
            'steps' => $steps,
            'files' => $this->files,
            '__version' => static::VERSION,
        ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        if (!Semver::satisfies($data['__version'], static::CONSTRAINTS_VERSION)) {
            $message = 'Cannot use incompatible version to unserialize stack trace (serialized by: %s, current: %s).';
            throw new \LogicException(sprintf($message, $data['__version'], static::VERSION));
        }
        $this->arrayStackTrace = $data['steps'];
        $this->unserialized = true;
        $this->files = isset($data['files']) ? $data['files'] : array();
    }

    public function __toString()
    {
        $result = array();
        $stepNumber = 0;
        $this->withoutArgs = true;
        foreach ($this as $step) {
            /** @var StepInterface $step */
            $line = '#' . $stepNumber;
            if ($step->hasCalledFunction()) {
                $line .= ' ' . $step->getCalledFunction()->getName() . '()';
            }
            if ($step->hasPlaceInCode()) {
                $placeInCode = $step->getPlaceInCode();
                $line .= ' at ' . $placeInCode->getFileName() . ':' . $placeInCode->getLineNumber();
            }

            $result[] = $line;
            $stepNumber++;
        }
        $this->withoutArgs = false;

        return implode("\n", $result);
    }

    public function getId()
    {
        $lines = array();
        $this->withoutArgs = true;
        foreach ($this as $key => $step) {
            /** @var StepInterface $step */
            $line = $key . '#';

            if ($step->hasCalledFunction()) {
                $line .= $step->getCalledFunction()->getName();
            }

            if ($step->hasPlaceInCode()) {
                $placeInCode = $step->getPlaceInCode();
                $line .= '@' . $placeInCode->getFileName() . ':' . $placeInCode->getLineNumber();
            }

            $lines[] = $line;
        }
        $this->withoutArgs = false;

        return md5(implode('__', $lines));
    }

    public function setVarDumper(VarDumperInterface $varDumper)
    {
        $this->varDumper = $varDumper;
    }

    /**
     * @internal
     * @param array $step
     * @param bool $toSerialize
     * @return array
     */
    public function convertStep(array $step, $toSerialize = false)
    {
        $result = array();
        foreach ($step as $key => $value) {
            $result[$key] = $value;
        }

        if ($this->withoutArgs) {
            $result['args'] = array();
        } else if (empty($result[Constants::KEY_ARGS_CONVERTED]) && isset($result['args'])) {
            $result['args'] = $this->convertArgs($result['args']);
            $result[Constants::KEY_ARGS_CONVERTED] = true;
        }

        if (
            !$toSerialize
            && !isset($result[Constants::KEY_FILE_OBJECT])
            && isset($result['file'])
            && isset($this->files[$result['file']])
        ) {
            $result[Constants::KEY_FILE_OBJECT] = $this->files[$result['file']];
        }

        if (isset($result['object'])) {
            unset($result['object']);
        }

        return $result;
    }

    private function getVarDumper()
    {
        if (is_null($this->varDumper)) {
            $this->varDumper = new LightVarDumper();
        }

        return $this->varDumper;
    }

    private function convertArgs(array $args)
    {
        /**
         * input has to be copied to different array,
         * because array $args returned by debug_backtrace function contains references from PHP 7.0
         */
        $result = array();

        foreach ($args as $key => $value) {
            $result[$key] = $this->convertArg($value);
        }

        return $result;
    }

    private function convertArg($value)
    {
        if (is_scalar($value)) {
            return new Value($value, $this->getVarDumper());
        }

        return new DeserializedValue($this->getVarDumper()->getDump($value));
    }
}
