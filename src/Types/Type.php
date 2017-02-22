<?php

namespace Awesomite\StackTrace\Types;

/**
 * @internal
 */
class Type implements TypeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * Type constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
