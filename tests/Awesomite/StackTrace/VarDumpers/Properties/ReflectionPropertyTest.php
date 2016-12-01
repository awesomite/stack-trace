<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

class ReflectionPropertyTest extends \PHPUnit_Framework_TestCase
{
    public static $static;

    public $public;
    protected $protected;
    private $private;

    /**
     * @dataProvider providerVisibility
     *
     * @param \ReflectionProperty $reflection
     * @param $object
     * @param bool $isPrivate
     * @param bool $isProtected
     * @param bool $isPublic
     */
    public function testVisibility(\ReflectionProperty $reflection, $object, $isPrivate, $isProtected, $isPublic)
    {
        $property = new ReflectionProperty($reflection, $object);
        $this->assertSame($isPrivate, $property->isPrivate());
        $this->assertSame($isProtected, $property->isProtected());
        $this->assertSame($isPublic, $property->isPublic());
    }

    public function providerVisibility()
    {
        $object = new \ReflectionObject($this);

        return array(
            array($object->getProperty('public'), $this, false, false, true),
            array($object->getProperty('protected'), $this, false, true, false),
            array($object->getProperty('private'), $this, true, false, false),
        );
    }

    /**
     * @dataProvider providerIsStatic
     *
     * @param \ReflectionProperty $reflection
     * @param $object
     * @param bool $isStatic
     */
    public function testIsStatic(\ReflectionProperty $reflection, $object, $isStatic)
    {
        $property = new ReflectionProperty($reflection, $object);
        $this->assertSame($isStatic, $property->isStatic());
    }

    public function providerIsStatic()
    {
        $object = new \ReflectionObject($this);

        return array(
            array($object->getProperty('static'), $this, true),
            array($object->getProperty('public'), $this, false),
        );
    }

    /**
     * @dataProvider providerGetValue
     *
     * @param ReflectionProperty $property
     * @param $expectedValue
     */
    public function testGetValue(ReflectionProperty $property, $expectedValue)
    {
        $this->assertSame($expectedValue, $property->getValue());
    }

    public function providerGetValue()
    {
        $object = new \ReflectionObject($this);
        $this->testVariable = 'test value';
        $this->private = mt_rand(1, 1000);

        return array(
            array(new ReflectionProperty($object->getProperty('public'), $this), null),
            array(new ReflectionProperty($object->getProperty('testVariable'), $this), $this->testVariable),
            array(new ReflectionProperty($object->getProperty('private'), $this), $this->private),
        );
    }

    /**
     * @dataProvider providerGetDeclaringClass
     *
     * @param ReflectionProperty $property
     * @param $declaringClass
     */
    public function testGetDeclaringClass(ReflectionProperty $property, $declaringClass)
    {
        $this->assertSame($declaringClass, $property->getDeclaringClass());
    }

    public function providerGetDeclaringClass()
    {
        $reflectionThis = new \ReflectionObject($this);

        $std = new \stdClass();
        $std->foo = 'bar';
        $reflectionStd = new \ReflectionObject($std);

        $child = new TestChild();
        $reflectionChild = new \ReflectionObject($child);

        return array(
            array(new ReflectionProperty($reflectionThis->getProperty('public'), $this), get_class($this)),
            array(new ReflectionProperty($reflectionStd->getProperty('foo'), $std), get_class($std)),
            array(
                new ReflectionProperty($reflectionChild->getProperty('foo'), $reflectionChild),
                get_class(new TestParent())
            ),
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