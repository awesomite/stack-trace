<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Exceptions\LogicException;

class PropertyTest extends BaseTestCase
{
    public static $static;

    public $public;
    protected $protected;
    private $private;

    /**
     * @dataProvider providerGetValue
     *
     * @param PropertyInterface $property
     * @param string $name
     * @param $value
     * @param bool $hasReflection
     */
    public function testGetValue(PropertyInterface $property, $name, $value, $hasReflection = true)
    {
        $this->assertSame($name, $property->getName());
        $this->assertSame($value, $property->getValue());
        $this->assertSame($hasReflection, $property->hasReflection());

        if (!$hasReflection) {
            $exception = new LogicException();
            $this->setExpectedException(get_class($exception));
        }
        $this->assertInstanceOf('ReflectionProperty', $property->getReflection());
    }

    public function providerGetValue()
    {
        $object = new \ReflectionObject($this);
        $this->testVariable = 'test value';
        $this->private = mt_rand(1, 1000);
        $this->protected = mt_rand(1, 1000);
        $randValue = mt_rand(1, 1000);

        return array(
            array(new ReflectionProperty($object->getProperty('public'), $this), 'public', null),
            array(
                new ReflectionProperty($object->getProperty('testVariable'), $this),
                'testVariable',
                $this->testVariable,
            ),
            array(new ReflectionProperty($object->getProperty('private'), $this), 'private', $this->private),
            array(new ReflectionProperty($object->getProperty('protected'), $this), 'protected', $this->protected),
            array(new VarProperty('varProperty', $randValue), 'varProperty', $randValue, false),
        );
    }

    /**
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     */
    public function testInvalidConstructor()
    {
        $object = new \ReflectionObject($this);

        new ReflectionProperty(
            $object->getProperty('public'),
            false
        );
    }
}