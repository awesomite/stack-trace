<?php

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Arguments\Values\DeserializedValue;
use Awesomite\StackTrace\Arguments\Values\Value;
use Awesomite\StackTrace\Steps\Step;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\StackTrace\VarDumpers\LightVarDumper;
use Awesomite\StackTrace\VarDumpers\VarDumperInterface;
use Composer\Semver\Semver;

/**
 * @internal
 */
class StackTrace implements StackTraceInterface
{
    const VERSION = '0.3.2';
    const CONSTRAINTS_VERSION = '^0.1.0|^0.2.0|^0.3.0';

    private $arrayStackTrace;

    private $withoutArgs = false;

    /**
     * @var VarDumperInterface
     */
    private $varDumper;

    public function __construct(array $arrayStackTrace)
    {
        $this->arrayStackTrace = $arrayStackTrace;
    }

    public function getIterator()
    {
        $result = array();
        foreach ($this->arrayStackTrace as $arrayStep) {
            $result[] = new Step($this->convertStep($arrayStep));
        }

        return new \ArrayIterator($result);
    }

    public function count()
    {
        return count($this->arrayStackTrace);
    }

    public function serialize()
    {
        $steps = array();
        foreach ($this->arrayStackTrace as $step) {
            $steps[] = $this->convertStep($step);
        }

        return serialize(array(
            'steps' => $steps,
            '__version' => static::VERSION,
        ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        if (!Semver::satisfies($data['__version'], static::CONSTRAINTS_VERSION)) {
            // @codeCoverageIgnoreStart
            $message = 'Cannot use incompatible version to unserialize stack trace (serialized by: %s, current: %s).';
            throw new \LogicException(sprintf($message, $data['__version'], static::VERSION));
            // @codeCoverageIgnoreEnd
        }
        $this->arrayStackTrace = $data['steps'];
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

    private function getVarDumper()
    {
        return $this->varDumper ?: new LightVarDumper();
    }

    private function convertStep(array $step)
    {
        $result = array();
        foreach ($step as $key => $value) {
            $result[$key] = $value;
        }

        if ($this->withoutArgs) {
            $result['args'] = array();
        }
        else if (empty($result[Constants::KEY_ARGS_CONVERTED]) && isset($result['args'])) {
            $result['args'] = $this->convertArgs($result['args']);
            $result[Constants::KEY_ARGS_CONVERTED] = true;
        }

        if (!empty($result['file']) && is_file($result['file']) && !isset($result[Constants::KEY_FILE_CONTENTS])) {
            $result[Constants::KEY_FILE_CONTENTS] = file_get_contents($result['file']);
        }

        if (isset($result['object'])) {
            unset($result['object']);
        }

        return $result;
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