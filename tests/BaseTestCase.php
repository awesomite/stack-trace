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

/**
 * @internal
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');

        $factoryReflection = new \ReflectionClass(new StackTraceFactory());
        $rootClassRef = $factoryReflection->getProperty('rootExceptionClass');
        $rootClassRef->setAccessible(true);
        $rootClassRef->setValue(null);
    }
}
