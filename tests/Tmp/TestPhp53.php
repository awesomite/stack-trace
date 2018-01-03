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
class TestPhp53
{
    public function argumentArray(array $argument)
    {
    }

    public function argumentClass(TestPhp53 $argument)
    {
    }

    public function argumentInvalidClass(InvalidClass $argument)
    {
    }

    public function argumentWithoutType($argument)
    {
    }

    public function argumentReference(&$reference)
    {
    }

    public function argumentDefaultValue($argument = 'test')
    {
    }
}
