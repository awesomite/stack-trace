<?php

namespace Awesomite\StackTrace\Arguments\Values;

/**
 * @internal
 */
class DeserializedValue implements ValueInterface
{
    private $dumpedVariable;

    /**
     * DeserializedValue constructor.
     * @param string $dump
     */
    public function __construct($dump)
    {
        $this->dumpedVariable = $dump;
    }

    public function __toString()
    {
        return $this->getDump();
    }

    public function dump()
    {
        echo $this->dumpedVariable;
    }

    public function getDump()
    {
        return $this->dumpedVariable;
    }

    public function getRealValue()
    {
        throw new CannotRestoreValueException('Cannot restore value!');
    }

    public function isRealValueReadable()
    {
        return false;
    }
}
