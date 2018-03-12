<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require \implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'vendor', 'autoload.php'));
require __DIR__ . DIRECTORY_SEPARATOR . 'StackTracePrinter.php';

/**
 * @internal
 *
 * @param $arg1
 * @param $arg2
 */
function myFirstFunction($arg1, $arg2)
{
    $callable = function () {
        mySecondFunction('foo', 'bar');
    };
    \call_user_func($callable, 'redundant');
}

/**
 * @internal
 *
 * @param $foo
 * @param $bar
 */
function mySecondFunction($foo, $bar)
{
    myThirdFunction(\tmpfile(), M_PI);
}

/**
 * @internal
 *
 * @param $argument1
 * @param $argument2
 */
function myThirdFunction($argument1, $argument2)
{
    $printer = new StackTracePrinter();
    $printer->printStackTrace();
}

myFirstFunction('hello', 'world');

/*

Output:

#1 Awesomite\StackTrace\StackTraceFactory->create($stepLimit, $ignoreArgs)
  Place in code:
    (...)/stack-trace/examples/StackTracePrinter.php:25
  Arguments:
    undefined
    undefined
#2 StackTracePrinter->printStackTrace()
  Place in code:
    (...)/stack-trace/examples/read-arguments.php:46
#3 myThirdFunction($argument1, $argument2)
  Place in code:
    (...)/stack-trace/examples/read-arguments.php:34
  Arguments:
    resource #10 of type stream
    M_PI
#4 mySecondFunction($foo, $bar)
  Place in code:
    (...)/stack-trace/examples/read-arguments.php:23
  Arguments:
    “foo”
    “bar”
#5 myFirstFunction($arg1, $arg2)
  Place in code:
    (...)/stack-trace/examples/read-arguments.php:49
  Arguments:
    “hello”
    “world”

*/
