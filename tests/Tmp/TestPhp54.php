<?php

namespace Awesomite\StackTrace\Tmp;

/**
 * @internal
 */
class TestPhp54
{
    public function argumentCallable(callable $argument) {}

    public function argumentDefaultConstant($argument = \PHP_VERSION) {}
}
