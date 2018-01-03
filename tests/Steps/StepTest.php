<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Steps;

use Awesomite\StackTrace\Arguments\ArgumentsInterface;
use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Exceptions\LogicException;
use Awesomite\StackTrace\SourceCode\PlaceInCodeInterface;

/**
 * @internal
 */
class StepTest extends BaseTestCase
{
    /**
     * @dataProvider providerGetArguments
     *
     * @param Step $step
     */
    public function testGetArguments(Step $step)
    {
        $this->assertTrue($step->getArguments() instanceof ArgumentsInterface);
    }

    public function providerGetArguments()
    {
        $result = array();
        foreach (\debug_backtrace() as $arrayStep) {
            $result[] = array(new Step($arrayStep));
        }

        return $result;
    }

    /**
     * @dataProvider providerHasPlaceInCode
     *
     * @param Step $step
     * @param bool $hasPlaceInCode
     */
    public function testHasPlaceInCode(Step $step, $hasPlaceInCode)
    {
        $this->assertSame($hasPlaceInCode, $step->hasPlaceInCode());
        if (!$hasPlaceInCode) {
            $exception = new LogicException();
            $this->setExpectedException(\get_class($exception));
        }
        $this->assertTrue($step->getPlaceInCode() instanceof PlaceInCodeInterface);
    }

    public function providerHasPlaceInCode()
    {
        $hasPlaceInCode = array(
            'file' => __FILE__,
            'line' => __LINE__,
            'args' => array(),
        );

        $hasNotPlaceInCode = array('args' => array());

        return array(
            array(new Step($hasPlaceInCode), true),
            array(new Step($hasNotPlaceInCode), false),
        );
    }

    /**
     * @dataProvider providerGetCalledFunction
     *
     * @param Step   $step
     * @param bool   $hasFunction
     * @param string $expectedName
     */
    public function testGetCalledFunction(Step $step, $hasFunction, $expectedName = '')
    {
        if (!$hasFunction) {
            $exception = new LogicException();
            $this->setExpectedException(\get_class($exception), 'There is no called function for this step!');
        }
        $this->assertSame($hasFunction, $step->hasCalledFunction());
        $this->assertSame($expectedName, $step->getCalledFunction()->getName());
    }

    public function providerGetCalledFunction()
    {
        return array(
            array(new Step(array('function' => 'strpos')), true, 'strpos'),
            array(new Step(array('function' => '{closure}')), true, '{closure}'),
            array(new Step(array()), false),
        );
    }
}
