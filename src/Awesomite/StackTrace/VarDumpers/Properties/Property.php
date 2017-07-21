<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
class Property implements PropertyInterface
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

    public function getReflection()
    {
        return $this->reflection;
    }
}