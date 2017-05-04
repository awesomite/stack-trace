<?php

namespace Awesomite\StackTrace\Functions;

use Awesomite\StackTrace\BaseTestCase;

class AFunctionProviders extends BaseTestCase
{
    public function providerGetName()
    {
        return array(
            array(
                new AFunction(array('class' => 'MyClass', 'type' => '::', 'function' => 'myFunction')),
                'MyClass::myFunction'
            ),
            array(
                new AFunction(array('class' => 'MyClass', 'type' => '->', 'function' => 'myFunction')),
                'MyClass->myFunction'
            ),
            array(new AFunction(array('function' => 'strpos')), 'strpos'),
        );
    }

    public function providerIsClosure()
    {
        return array(
            array(new AFunction(array('function' => 'strpos')), false),
            array(new AFunction(array('function' => '{closure}')), true),
        );
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
        $testClassName = get_class(new TestClass());

        $deprecatedFunctions = array(
            'sayHello' => array('class' => $testClassName, 'function' => 'sayHello'),
            'sayGoodbye' => array('class' => $testClassName, 'function' => 'sayGoodbye'),
        );

        $functions = array(
            'welcome' => array('class' => $testClassName, 'function' => 'welcome'),
            '{closure}' => array('function' => '{closure}'),
        );

        foreach (array('call_user_method', 'ldap_sort', 'strpos', 'mb_ereg_replace') as $functionName) {
            if (function_exists($functionName)) {
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
            'class' => array(new AFunction(array('class' => __CLASS__, 'function' => __FUNCTION__)), true),
            'strpos' => array(new AFunction(array('function' => 'strpos')), true),
            '{closure}' => array(new AFunction(array('function' => '{closure}')), false),
        );
    }
}
