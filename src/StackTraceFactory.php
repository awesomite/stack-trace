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

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\VarDumperInterface;

class StackTraceFactory implements StackTraceFactoryInterface
{
    private static $rootExceptionClass = null;

    /**
     * @var VarDumperInterface
     */
    private $varDumper;

    public function __construct(VarDumperInterface $varDumper = null)
    {
        $this->varDumper = $varDumper ?: new LightVarDumper();
    }

    /**
     * {@inheritdoc}
     */
    public function create($stepLimit = 0, $ignoreArgs = false)
    {
        if (\version_compare(PHP_VERSION, '5.4') >= 0) {
            $options = 0;
            if ($ignoreArgs) {
                $options |= DEBUG_BACKTRACE_IGNORE_ARGS;
            }
            $arrayStackTrace = \debug_backtrace($options, $stepLimit);
            if ($ignoreArgs) {
                $this->removeArgs($arrayStackTrace);
            }

            return new StackTrace($arrayStackTrace, $this->varDumper);
        }

        $arrayStackTrace = \debug_backtrace($this->getOptionsForDebugBacktrace53());
        if ($stepLimit > 0) {
            $arrayStackTrace = \array_slice($arrayStackTrace, 0, $stepLimit, true);
        }
        if ($ignoreArgs) {
            $this->removeArgs($arrayStackTrace);
        }

        return new StackTrace($arrayStackTrace, $this->varDumper);
    }

    /**
     * {@inheritdoc}
     */
    public function createByThrowable($exception, $stepLimit = 0, $ignoreArgs = false)
    {
        $exceptionClass = $this->getRootExceptionClass();
        if (!\is_object($exception) || !$exception instanceof $exceptionClass) {
            throw new InvalidArgumentException(\sprintf(
                "Expected argument of type %s, %s given",
                $exceptionClass,
                \is_object($exception) ? \get_class($exception) : \gettype($exception)
            ));
        }

        return $this->createBy($exception, $stepLimit, $ignoreArgs);
    }

    /**
     * HHVM still does not support \Throwable interface
     *
     * @return null|string
     */
    private function getRootExceptionClass()
    {
        if (\is_null(self::$rootExceptionClass)) {
            $reflection = new \ReflectionClass('\Exception');
            $throwableExists = \interface_exists('\Throwable', false);
            self::$rootExceptionClass = $throwableExists && $reflection->implementsInterface('\Throwable')
                ? '\Throwable'
                : '\Exception';
        }

        return self::$rootExceptionClass;
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param int                   $stepLimit
     * @param bool                  $ignoreArgs
     *
     * @return StackTrace
     */
    private function createBy($exception, $stepLimit = 0, $ignoreArgs = false)
    {
        $trace = $exception->getTrace();
        $step = array(
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        );
        \array_unshift($trace, $step);

        if ($stepLimit > 0) {
            $trace = \array_slice($trace, 0, $stepLimit, true);
        }

        if ($ignoreArgs) {
            $this->removeArgs($trace);
        }

        return new StackTrace($trace, $this->varDumper);
    }

    private function removeArgs(array &$trace)
    {
        foreach ($trace as &$step) {
            if (isset($step['args'])) {
                unset($step['args']);
            }
        }
    }

    private function getOptionsForDebugBacktrace53()
    {
        return \version_compare(PHP_VERSION, '5.3.6') >= 0 ? 0 : false;
    }
}
