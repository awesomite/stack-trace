<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Functions;

use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\StackTraceFactory;

/**
 * @internal
 */
abstract class AFunctionProviders extends BaseTestCase
{
    public function providerGetName()
    {
        return array(
            array(
                new AFunction(array('class' => 'MyClass', 'type' => '::', 'function' => 'myFunction')),
                'MyClass::myFunction',
            ),
            array(
                new AFunction(array('class' => 'MyClass', 'type' => '->', 'function' => 'myFunction')),
                'MyClass->myFunction',
            ),
            array(new AFunction(array('function' => 'strpos')), 'strpos'),
        );
    }

    public function providerIsClosure()
    {
        $result = array(
            array(new AFunction(array('function' => 'strpos')), false),
            array(new AFunction(array('function' => '{closure}')), true),
        );

        if (\defined('HHVM_VERSION')) {
            array(new AFunction(array('function' => 'Closure$myFunction')), true);
        }

        $closure = function () use (&$result) {
            $factory = new StackTraceFactory();
            $trace = $factory->create(2);
            $i = 0;
            foreach ($trace as $step) {
                $result[] = array(
                    new AFunction(array('function' => $step->getCalledFunction()->getName())),
                    2 === ++$i,
                );
            }
        };
        \call_user_func($closure);

        return $result;
    }

    public function providerInClass()
    {
        return array(
            array(new AFunction(array('class' => __CLASS__, 'function' => __FUNCTION__)), true),
            array(new AFunction(array('function' => 'strpos')), false),
        );
    }

    public function providerIsKeyword()
    {
        return array(
            array(new AFunction(array('function' => 'include')), true),
            array(new AFunction(array('function' => 'strpos')), false),
        );
    }

    public function providerIsDeprecated()
    {
        $testClassName = \get_class(new TestClass());
        $deprecatedClassName = \get_class(new TestDeprecatedClass());

        $deprecatedFunctions = array(
            'sayHello'    => array('class' => $testClassName, 'function' => 'sayHello'),
            'sayGoodbye'  => array('class' => $testClassName, 'function' => 'sayGoodbye'),
            'doSomething' => array('class' => $deprecatedClassName, 'function' => 'doSomething'),
        );

        $functions = array(
            'welcome'             => array('class' => $testClassName, 'function' => 'welcome'),
            'bye'                 => array('class' => $testClassName, 'function' => 'bye'),
            '{closure}'           => array('function' => '{closure}'),
            'ArrayObject->append' => array('class' => 'ArrayObject', 'function' => 'append'),
        );

        foreach (array('call_user_method', 'ldap_sort', 'strpos', 'mb_ereg_replace') as $functionName) {
            if (\function_exists($functionName)) {
                $reflectionFunction = new \ReflectionFunction($functionName);
                if ($reflectionFunction->isDeprecated()) {
                    $deprecatedFunctions[$functionName] = array('function' => $functionName);
                } else {
                    $functions[$functionName] = array('function' => $functionName);
                }
            }
        }

        $result = array();

        foreach ($deprecatedFunctions as $key => $array) {
            $result[$key] = array(new AFunction($array), true);
        }

        foreach ($functions as $key => $array) {
            $result[$key] = array(new AFunction($array), false);
        }

        return $result;
    }

    public function providerReflection()
    {
        return array(
            'class'     => array(new AFunction(array('class' => __CLASS__, 'function' => __FUNCTION__)), true),
            'strpos'    => array(new AFunction(array('function' => 'strpos')), true),
            '{closure}' => array(new AFunction(array('function' => '{closure}')), false),
        );
    }
}
