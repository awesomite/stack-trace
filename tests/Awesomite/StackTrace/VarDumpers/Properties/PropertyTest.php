<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\BaseTestCase;

class PropertyTest extends BaseTestCase
{
    public static $static;

    public $public;
    protected $protected;
    private $private;

    /**
     * @dataProvider providerGetValue
     *
     * @param Property $property
     * @param $expectedValue
     */
    public function testGetValue(Property $property, $expectedValue)
    {
        $this->assertSame($expectedValue, $property->getValue());
    }

    public function providerGetValue()
    {
        $object = new \ReflectionObject($this);
        $this->testVariable = 'test value';
        $this->private = mt_rand(1, 1000);
        $this->protected = mt_rand(1, 1000);

        return array(
            array(new Property($object->getProperty('public'), $this), null),
            array(new Property($object->getProperty('testVariable'), $this), $this->testVariable),
            array(new Property($object->getProperty('private'), $this), $this->private),
            array(new Property($object->getProperty('protected'), $this), $this->protected),
        );
    }

    /**
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     */
    public function testInvalidConstructor()
    {
        $object = new \ReflectionObject($this);

        new Property(
            $object->getProperty('public'),
            false
        );
    }
}