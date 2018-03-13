<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Declarations;

use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Tmp\TestPhp53;
use Awesomite\StackTrace\Tmp\TestPhp54;
use Awesomite\StackTrace\Tmp\TestPhp56;
use Awesomite\StackTrace\Tmp\TestPhp70;
use Awesomite\StackTrace\Tmp\TestPhp71;
use Awesomite\StackTrace\Tmp\TestPhp72;

class DeclarationProviders extends BaseTestCase
{
    public function providerGetName()
    {
        $class = new \ReflectionClass($this);
        $method = $class->getMethod('testGetName');
        $parameters = $method->getParameters();
        $parameter = $parameters[0];

        return array(
            array(new Declaration($parameter), $parameter->getName()),
            array(new Declaration($parameter), 'declaration'),
        );
    }

    public function providerGetType()
    {
        $result = array();

        $testPhp53 = new TestPhp53();
        $class53 = new \ReflectionClass($testPhp53);

        $methodArray = $class53->getMethod('argumentArray');
        list($parameterArray) = $methodArray->getParameters();
        $result[] = array(new Declaration($parameterArray), true, 'array');

        $methodClass = $class53->getMethod('argumentClass');
        list($parameterClass) = $methodClass->getParameters();
        $result[] = array(new Declaration($parameterClass), true, $class53->getName());

        $methodInvalidClass = $class53->getMethod('argumentInvalidClass');
        list($paramInvalidClass) = $methodInvalidClass->getParameters();
        $expectedClassName = $class53->getNamespaceName() . '\\' . 'InvalidClass';
        $result[] = array(new Declaration($paramInvalidClass), true, $expectedClassName);

        $methodWithoutType = $class53->getMethod('argumentWithoutType');
        list($parameterWithoutName) = $methodWithoutType->getParameters();
        $result[] = array(new Declaration($parameterWithoutName), false);

        if (\version_compare(PHP_VERSION, '5.4') >= 0) {
            $testPhp54 = new TestPhp54();
            $class54 = new \ReflectionClass($testPhp54);
            $methodCallable = $class54->getMethod('argumentCallable');
            list($parameterCallable) = $methodCallable->getParameters();
            $result[] = array(new Declaration($parameterCallable), true, 'callable');
        }

        if (\version_compare(PHP_VERSION, '7.0')) {
            $class70 = new \ReflectionClass(new TestPhp70());
            list($parameterIterable) = $class70->getMethod('argumentInt')->getParameters();
            $result[] = array(new Declaration($parameterIterable), true, 'int');
        }

        // on version 7.1.0beta1 there is wrong type - array instead of iterable
        if (\version_compare(PHP_VERSION, '7.1.0RC1') >= 0 && !\defined('HHVM_VERSION')) {
            $class71 = new \ReflectionClass(new TestPhp71());
            list($parameterIterable) = $class71->getMethod('argumentIterable')->getParameters();
            $result[] = array(new Declaration($parameterIterable), true, 'iterable');
        }

        if (\version_compare(PHP_VERSION, '7.2') >= 0) {
            $class72 = new \ReflectionClass(new TestPhp72());
            list($parameterIterable) = $class72->getMethod('argumentObject')->getParameters();
            $result[] = array(new Declaration($parameterIterable), true, 'object');
        }

        return $result;
    }

    public function providerIsPassedByReference()
    {
        $class53 = new \ReflectionClass(new TestPhp53());
        list($passedByReference) = $class53->getMethod('argumentReference')->getParameters();
        list($passedByValue) = $class53->getMethod('argumentWithoutType')->getParameters();

        return array(
            array(new Declaration($passedByReference), true),
            array(new Declaration($passedByValue), false),
        );
    }

    public function providerIsVariadic()
    {
        $result = array();

        $class53 = new \ReflectionClass(new TestPhp53());
        list($normalParameter) = $class53->getMethod('argumentArray')->getParameters();
        $result[] = array(new Declaration($normalParameter), false);

        if (\version_compare(PHP_VERSION, '5.6') >= 0) {
            $class56 = new \ReflectionClass(new TestPhp56());
            list($variadicParameter) = $class56->getMethod('argumentVariadic')->getParameters();
            $result[] = array(new Declaration($variadicParameter), true);
        }

        return $result;
    }

    public function providerDefaultValue()
    {
        $result = array();
        $class53 = new \ReflectionClass(new TestPhp53());

        list($defaultValue) = $class53->getMethod('argumentDefaultValue')->getParameters();
        $result[] = array(new Declaration($defaultValue), true, 'test');

        list($noDefaultValue) = $class53->getMethod('argumentWithoutType')->getParameters();
        $result[] = array(new Declaration($noDefaultValue), false);

        return $result;
    }

    public function providerDefaultValueConstantName()
    {
        $result = array();

        $class53 = new \ReflectionClass(new TestPhp53());
        list($normalParameter) = $class53->getMethod('argumentArray')->getParameters();
        $result[] = array(new Declaration($normalParameter), false);

        if (\version_compare(PHP_VERSION, '5.4.6') >= 0 && !\defined('HHVM_VERSION')) {
            $class54 = new \ReflectionClass(new TestPhp54());
            list($defaultConstant) = $class54->getMethod('argumentDefaultConstant')->getParameters();
            $result[] = array(new Declaration($defaultConstant), true, 'PHP_VERSION');
        }

        return $result;
    }
}
