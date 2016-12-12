<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
class VarProperty implements PropertyInterface
{
    private $name;

    /**
     * VarProperty constructor.
     * @param string $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function hasReflection()
    {
        return false;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getReflection()
    {
        throw new LogicException("Reflection for property {$this->name} does not exist!");
    }

    public function getValue()
    {
        return $this->value;
    }
}