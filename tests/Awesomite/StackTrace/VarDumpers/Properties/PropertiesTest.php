<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerGetProperties
     *
     * @param $object
     */
    public function testGetProperties($object)
    {
        $properties = new Properties($object);
        foreach ($properties->getProperties() as $property) {
            $this->assertInstanceOf('Awesomite\StackTrace\VarDumpers\Properties\PropertyInterface', $property);
        }
    }

    public function providerGetProperties()
    {
        $obj = new \stdClass();
        $obj->foo = 'bar';

        return array(
            array(new \stdClass()),
            array($obj),
        );
    }

    /**
     * @dataProvider providerInvalidConstructor
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     *
     * @param $object
     */
    public function testInvalidConstructor($object)
    {
        new Properties($object);
    }

    public function providerInvalidConstructor()
    {
        return array(
            array(1),
            array(false),
            array(array()),
            array(null),
        );
    }
}