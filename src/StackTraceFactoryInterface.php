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

interface StackTraceFactoryInterface
{
    /**
     * @param int  $stepLimit
     * @param bool $ignoreArgs
     *
     * @return StackTraceInterface
     */
    public function create($stepLimit = 0, $ignoreArgs = false);

    /**
     * @param \Throwable|\Exception $exception
     * @param int                   $stepLimit
     * @param bool                  $ignoreArgs
     *
     * @return StackTraceInterface
     */
    public function createByThrowable($exception, $stepLimit = 0, $ignoreArgs = false);
}
