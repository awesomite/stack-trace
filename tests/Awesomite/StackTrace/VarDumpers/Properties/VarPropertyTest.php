<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\BaseTestCase;

class VarPropertyTest extends BaseTestCase
{
    /**
     * @dataProvider providerInvalidConstructor
     *
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid value of $visibility!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\StackTrace\VarDumpers\Properties\VarProperty');
        $reflection->newInstanceArgs(func_get_args());
    }

    public function providerInvalidConstructor()
    {
        return array(
            array('name', 'value', false, get_class($this)),
            array('name', 'value', new \stdClass(), get_class($this)),
        );
    }
}