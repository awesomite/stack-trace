<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Constants;
use Awesomite\StackTrace\StackTraceFactory;

class PlaceInCodeProviders extends BaseTestCase
{
    public function providerInvalidLineLimit()
    {
        return array(
            array(PHP_INT_MAX),
            array(Constants::MAX_LINE_THRESHOLD * 2 + 1),
        );
    }

    public function providerGetFile()
    {
        return array(
            array(new PlaceInCode(__FILE__, __LINE__), __FILE__, __LINE__),
            array(new PlaceInCode('/tmp/foo', 1), '/tmp/foo', 1),
        );
    }

    public function providerGetAdjacentCode()
    {
        return array(
            array(new PlaceInCode(__FILE__, __LINE__)),
            array(new PlaceInCode(__FILE__, 1)),
        );
    }

    public function providerInvalidFile()
    {
        return array(
            array(new PlaceInCode(__DIR__, 1)),
            array(new PlaceInCode(\dirname(__DIR__), 1)),
        );
    }

    public function providerInvalidLine()
    {
        $result = array();

        $result[] = array(new PlaceInCode(__FILE__, 1000));

        $reflectionClass = new \ReflectionClass(new StackTraceFactory());
        $contents = \file_get_contents($reflectionClass->getFileName());
        $lines = \explode("\n", $contents);
        $result[] = array(new PlaceInCode($reflectionClass->getFileName(), \count($lines) + 1));

        return $result;
    }

    public function providerMove()
    {
        $linesCounter = \count(\explode("\n", \file_get_contents(__FILE__)));

        return array(
            array(new PlaceInCode(__FILE__, $linesCounter), 20, $linesCounter - 19, $linesCounter),
            array(new PlaceInCode(__FILE__, 1), 20, 1, 20),
        );
    }
}
