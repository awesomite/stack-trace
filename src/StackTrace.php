<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace;

use Awesomite\Iterators\CallbackIterator;
use Awesomite\StackTrace\Arguments\Values\DeserializedValue;
use Awesomite\StackTrace\Arguments\Values\Value;
use Awesomite\StackTrace\Exceptions\LogicException;
use Awesomite\StackTrace\SourceCode\File;
use Awesomite\StackTrace\Steps\Step;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\VarDumperInterface;
use Composer\Semver\Semver;

/**
 * @internal
 */
final class StackTrace implements StackTraceInterface
{
    const VERSION             = '1.3.1';
    const CONSTRAINTS_VERSION = '^1.0.0';

    private $arrayStackTrace;

    private $withoutArgs = false;

    private $files = array();

    private $unserialized = false;

    /**
     * @var null|VarDumperInterface
     */
    private $varDumper;

    /**
     * @var false|int
     */
    private $maxSerializableStringLen;

    /**
     * @param array              $arrayStackTrace
     * @param VarDumperInterface $varDumper
     * @param null|int           $maxSerializableStringLen
     */
    public function __construct(
        array $arrayStackTrace,
        VarDumperInterface $varDumper,
        $maxSerializableStringLen
    ) {
        $this->arrayStackTrace = new \ArrayObject($arrayStackTrace);
        $this->varDumper = $varDumper;
        $this->maxSerializableStringLen = $maxSerializableStringLen;
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
        return \count($this->arrayStackTrace);
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
                if ($fileName && \is_file($fileName)) {
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

        return \serialize(array(
            'steps'     => $steps,
            'files'     => $this->files,
            '__version' => static::VERSION,
        ));
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        if (!Semver::satisfies($data['__version'], static::CONSTRAINTS_VERSION)) {
            throw new LogicException(\sprintf(
                'Cannot use incompatible version to unserialize stack trace (serialized by: %s, current: %s).',
                $data['__version'],
                static::VERSION
            ));
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
                if ($step->hasPlaceInCode()) {
                    $line .= ' at';
                }
            }
            if ($step->hasPlaceInCode()) {
                $placeInCode = $step->getPlaceInCode();
                $line .= ' ' . $placeInCode->getFileName() . ':' . $placeInCode->getLineNumber();
            }

            $result[] = $line;
            $stepNumber++;
        }
        $this->withoutArgs = false;

        return \implode("\n", $result);
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

        return \md5(\implode('__', $lines));
    }

    public function getVarDumper()
    {
        if (\is_null($this->varDumper)) {
            $this->varDumper = new LightVarDumper();
        }

        return $this->varDumper;
    }

    public function setVarDumper(VarDumperInterface $varDumper)
    {
        $this->varDumper = $varDumper;
    }

    /**
     * @internal
     *
     * @param array $step
     * @param bool  $toSerialize
     *
     * @return array
     */
    public function convertStep(array $step, $toSerialize = false)
    {
        if ($this->withoutArgs) {
            $step['args'] = array();
        } else {
            if (empty($step[Constants::KEY_ARGS_CONVERTED]) && isset($step['args'])) {
                $maxArgs = null;
                if (\version_compare(\PHP_VERSION, '5.6') >= 0) {
                    $fakeStep = new Step($step);
                    $reflectionFn = $fakeStep->hasCalledFunction() && $fakeStep->getCalledFunction()->hasReflection()
                        ? $fakeStep->getCalledFunction()->getReflection()
                        : null;
                    if (!\is_null($reflectionFn) && $reflectionFn->isVariadic()) {
                        $maxArgs = \count($reflectionFn->getParameters());
                    }
                }

                $step['args'] = $this->convertArgs($step['args'], $maxArgs);
                $step[Constants::KEY_ARGS_CONVERTED] = true;
            }
        }

        if (
            !$toSerialize
            && !isset($step[Constants::KEY_FILE_OBJECT])
            && isset($step['file'])
            && isset($this->files[$step['file']])
        ) {
            $step[Constants::KEY_FILE_OBJECT] = $this->files[$step['file']];
        }

        if (isset($step['object'])) {
            unset($step['object']);
        }

        return $step;
    }

    /**
     * @param array    $inputArgs
     * @param null|int $maxArgs
     *
     * @return array
     */
    private function convertArgs(array $inputArgs, $maxArgs)
    {
        /**
         * debug_backtrace()[$x]['args'] can contain references
         */
        $args = array();
        foreach ($inputArgs as $key => $value) {
            $args[$key] = $value;
        }

        if (!\is_null($maxArgs) && $maxArgs <= \count($args)) {
            $preparedCopy = $args;
            $args = \array_slice($preparedCopy, 0, $maxArgs - 1);
            $args[] = \array_slice($preparedCopy, $maxArgs - 1);
        }

        foreach ($args as $key => $value) {
            $args[$key] = $this->convertArg($value);
        }

        return $args;
    }

    private function convertArg($value)
    {
        $isSerializable = \is_string($value)
            ? (null === $this->maxSerializableStringLen || \strlen($value) <= $this->maxSerializableStringLen)
            : (\is_scalar($value) || null === $value);

        return $isSerializable
            ? new Value($value, $this->getVarDumper())
            : new DeserializedValue($this->getVarDumper()->dumpAsString($value));
    }
}
