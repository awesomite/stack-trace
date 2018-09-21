<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Tmp;

/**
 * @internal
 */
final class TestPhp54
{
    public function argumentCallable(callable $argument)
    {
    }

    public function argumentDefaultConstant($argument = \PHP_VERSION)
    {
    }
}
