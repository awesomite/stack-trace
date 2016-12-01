<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
class Properties implements PropertiesInterface
{
    private $object;

    /**
     * Properties constructor.
     * @param $object
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Argument $object is not an object!');
        }
        $this->object = $object;
    }

    public function getProperties()
    {
        $object = $this->object;
        $result = array_map(function ($property) use ($object) {
            return new ReflectionProperty($property, $object);
        }, $this->getDeclaredProperties());

        return array_values($result);
    }

    private function getDeclaredProperties()
    {
        $reflection = new \ReflectionObject($this->object);
        $result = array();
        do {
            $result += $this->getDeclaredPropertiesForReflection($reflection);
        } while ($reflection = $reflection->getParentClass());

        return $result;
    }

    private function getDeclaredPropertiesForReflection(\ReflectionClass $reflection)
    {
        $result = array();
        foreach ($reflection->getProperties() as $property) {
            $result[$property->getDeclaringClass()->getName() . '__' . $property->getName()] = $property;
        }

        return $result;
    }
}