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
        $atLeast70 = version_compare(PHP_VERSION, '7.0') >= 0;
        if (!$atLeast70 && !defined('HHVM_VERSION')) {
            $deprecatedFunctions['call_user_method'] = array('function' => 'call_user_method');
        }
        if ($atLeast70 && in_array('ldap', get_loaded_extensions())) {
            $deprecatedFunctions['ldap_sort'] = array('function' => 'ldap_sort');
        }

        $functions = array(
            'welcome' => array('class' => $testClassName, 'function' => 'welcome'),
            'strpos' => array('function' => 'strpos'),
            '{closure}' => array('function' => '{closure}'),
        );

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