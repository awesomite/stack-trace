<?php

namespace Awesomite\StackTrace\Tmp;

/**
 * @internal
 */
class TestPhp53
{
    public function argumentArray(array $argument) {}

    public function argumentClass(TestPhp53 $argument) {}

    public function argumentInvalidClass(InvalidClass $argument) {}

    public function argumentWithoutType($argument) {}

    public function argumentReference(&$reference) {}

    public function argumentDefaultValue($argument = 'test') {}
}