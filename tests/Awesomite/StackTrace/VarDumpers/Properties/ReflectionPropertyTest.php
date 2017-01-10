<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\BaseTestCase;

class ReflectionPropertyTest extends BaseTestCase
{
    private $testProperty;

    /**
     * @dataProvider providerInvalidConstructor
     *
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument $object is not an object!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\StackTrace\VarDumpers\Properties\ReflectionProperty');
        $reflection->newInstanceArgs(func_get_args());
    }

    public function providerInvalidConstructor()
    {
        $reflection = new \ReflectionProperty($this, 'testProperty');
        return array(
            array($reflection, false),
            array($reflection, 1),
            array($reflection, get_class($this)),
        );
    }
}