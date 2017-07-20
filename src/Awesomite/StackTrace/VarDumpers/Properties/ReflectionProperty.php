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

    public function getValue()
    {
        $this->reflection->setAccessible(true);
        set_error_handler(function () {});
        $result = null;
        try {
            $result = $this->reflection->getValue($this->object);
        } catch (\Exception $e) {
        } catch (\Throwable $e) {
        }
        restore_error_handler();

        return $result;
    }

    public function getName()
    {
        return $this->reflection->getName();
    }

    public function isStatic()
    {
        return $this->reflection->isStatic();
    }

    public function isVirtual()
    {
        $class = new \ReflectionClass(get_class($this->object));

        do {
            if ($class->hasProperty($this->getName())) {
                return false;
            }
        } while ($class = $class->getParentClass());

        return true;
    }

    public function isPublic()
    {
        return $this->reflection->isPublic();
    }

    public function isProtected()
    {
        return $this->reflection->isProtected();
    }

    public function isPrivate()
    {
        return $this->reflection->isPrivate();
    }

    public function getDeclaringClass()
    {
        return $this->reflection->getDeclaringClass()->getName();
    }
}