<?php

namespace Awesomite\StackTrace\Arguments;

use Awesomite\StackTrace\Arguments\Declarations\DeclarationInterface;
use Awesomite\StackTrace\Arguments\Values\ValueInterface;
use Awesomite\StackTrace\Exceptions\LogicException;

interface ArgumentInterface
{
    /**
     * @return bool
     */
    public function hasValue();

    /**
     * @throws LogicException
     *
     * @return ValueInterface
     */
    public function getValue();

    /**
     * @return bool
     */
    public function hasDeclaration();

    /**
     * @throws LogicException
     *
     * @return DeclarationInterface
     */
    public function getDeclaration();
}
