<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments;

use Awesomite\StackTrace\Arguments\Values\Value;
use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Functions\AFunction;
use Awesomite\StackTrace\Functions\FunctionInterface;
use Awesomite\StackTrace\Tmp\TestPhp53;
use Awesomite\StackTrace\Tmp\TestPhp56;

/**
 * @internal
 */
final class ArgumentsTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param Arguments $arguments
     * @param           $count
     */
    public function testAll(Arguments $arguments, $count)
    {
        $this->assertSame($count, \count($arguments));
        $this->assertSame(\count($arguments), \count(\iterator_to_array($arguments)));
        foreach ($arguments as $argument) {
            $this->assertTrue($argument instanceof ArgumentInterface);
        }
    }

    public function providerAll()
    {
        $result = array(
            'default' => $this->providerDefault(),
            'tooMany' => $this->providerTooManyArguments(),
            'tooFew'  => $this->providerTooFewArguments(),
            'closure' => $this->providerClosure(),
        );

        if (\version_compare(PHP_VERSION, '5.6') >= 0) {
            $result['variadic'] = $this->providerVariadic();
        }

        return $result;
    }

    private function providerDefault()
    {
        return array(
            $this->createByRawValues(array('value'), $this->createFunctionArray()),
            1,
        );
    }

    private function providerVariadic()
    {
        $function = new AFunction(array(
            'class'    => \get_class(new TestPhp56()),
            'function' => 'argumentVariadic',
        ));

        return array(
            $this->createByRawValues(array(array('1', '2', '3')), $function),
            1,
        );
    }

    private function providerTooManyArguments()
    {
        return array(
            $this->createByRawValues(array(1, 2, 3), $this->createFunctionArray()),
            3,
        );
    }

    private function providerTooFewArguments()
    {
        return array(
            new Arguments(array(), $this->createFunctionArray()),
            1,
        );
    }

    private function providerClosure()
    {
        return array($this->createByRawValues(array(1, 2, 3)), 3);
    }

    private function createFunctionArray()
    {
        return new AFunction(array(
            'class'    => \get_class(new TestPhp53()),
            'function' => 'argumentArray',
        ));
    }

    private function createByRawValues(array $values = null, FunctionInterface $function = null)
    {
        $objects = \array_map(function ($input) {
            return new Value($input);
        }, $values);

        return new Arguments($objects, $function);
    }
}
