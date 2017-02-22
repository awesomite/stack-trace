<?php

namespace Awesomite\StackTrace\Arguments\Values;

interface ValueInterface
{
    /**
     * @throws CannotRestoreValueException
     *
     * @return mixed
     */
    public function getRealValue();

    /**
     * @return bool
     */
    public function isRealValueReadable();

    public function dump();

    /**
     * @return string
     */
    public function getDump();

    public function __toString();
}
