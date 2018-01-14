<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Values;

/**
 * @internal
 */
class DeserializedValue implements ValueInterface
{
    private $dumpedVariable;

    /**
     * @param string $dump
     */
    public function __construct($dump)
    {
        $this->dumpedVariable = $dump;
    }

    public function __toString()
    {
        return $this->dumpAsString();
    }

    public function dump()
    {
        echo $this->dumpedVariable;
    }

    public function dumpAsString()
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
