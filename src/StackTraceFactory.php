<?php

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

class StackTraceFactory
{
    /**
     * @param int $stepLimit
     * @param bool $ignoreArgs
     * @return StackTraceInterface
     */
    public function create($stepLimit = 0, $ignoreArgs = false)
    {
        if (version_compare(PHP_VERSION, '5.4') >= 0) {
            $options = 0;
            if ($ignoreArgs) {
                $options |= DEBUG_BACKTRACE_IGNORE_ARGS;
            }
            return new StackTrace(debug_backtrace($options, $stepLimit));
        }

        $options = $this->getOptionsForDebugBacktrace53();
        $arrayStackTrace = debug_backtrace($options);
        if ($stepLimit > 0) {
            $arrayStackTrace = array_slice(debug_backtrace($options), 0, $stepLimit, true);
        }
        if ($ignoreArgs) {
            foreach ($arrayStackTrace as &$step) {
                if (isset($step['args'])) {
                    unset($step['args']);
                }
            }
        }

        return new StackTrace($arrayStackTrace);
    }

    /**
     * @param \Throwable|\Exception $exception
     * @param int $stepLimit
     * @param bool $ignoreArgs
     * @return StackTraceInterface
     */
    public function createByThrowable($exception, $stepLimit = 0, $ignoreArgs = false)
    {
        $exceptionClass = version_compare(PHP_VERSION, '7.0') >= 0 ? '\Throwable' : '\Exception';
        if (!$exception instanceof $exceptionClass) {
            throw new InvalidArgumentException("Argument should be an instance of {$exceptionClass}!");
        }

        return $this->createBy($exception, $stepLimit, $ignoreArgs);
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param int $stepLimit
     * @param bool $ignoreArgs
     * @return StackTrace
     */
    private function createBy($exception, $stepLimit = 0, $ignoreArgs = false)
    {
        $trace = $exception->getTrace();
        $step = array(
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        );
        array_unshift($trace, $step);

        if ($stepLimit > 0) {
            $trace = array_slice($trace, 0, $stepLimit, true);
        }

        if ($ignoreArgs) {
            foreach ($trace as &$step) {
                if (isset($step['args'])) {
                    unset($step['args']);
                }
            }
        }

        return new StackTrace($trace);
    }

    private function getOptionsForDebugBacktrace53()
    {
        return version_compare(PHP_VERSION, '5.3.6') >= 0 ? 0 : false;
    }
}