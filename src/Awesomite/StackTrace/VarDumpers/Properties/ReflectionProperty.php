<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
class ReflectionProperty implements PropertyInterface
{
    private $reflection;

    private $object;

    public function __construct(\ReflectionProperty $property, $object)
    {
        $this->reflection = $property;
        if (!is_object($object)) {
            throw new InvalidArgumentException('Argument $object is not an object!');
        }
        $this->object = $object;
    }

    public function getName()
    {
        return $this->reflection->getName();
    }

    public function isPrivate()
    {
        return $this->reflection->isPrivate();
    }

    public function isProtected()
    {
        return $this->reflection->isProtected();
    }

    public function isPublic()
    {
        return $this->reflection->isPublic();
    }

    public function isStatic()
    {
        return $this->reflection->isStatic();
    }

    public function getValue()
    {
        $this->reflection->setAccessible(true);
        $result = $this->reflection->getValue($this->object);

        return $result;
    }

    public function getDeclaringClass()
    {
        return $this->reflection->getDeclaringClass()->getName();
    }
}