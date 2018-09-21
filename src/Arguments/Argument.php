<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments;

use Awesomite\StackTrace\Arguments\Declarations\DeclarationInterface;
use Awesomite\StackTrace\Arguments\Values\ValueInterface;
use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
final class Argument implements ArgumentInterface
{
    /**
     * @var DeclarationInterface|null
     */
    private $declaration;

    /**
     * @var ValueInterface|null
     */
    private $value;

    /**
     * @codeCoverageIgnore
     *
     * @param DeclarationInterface|null $declaration
     * @param ValueInterface|null       $value
     */
    public function __construct(DeclarationInterface $declaration = null, ValueInterface $value = null)
    {
        $this->declaration = $declaration;
        $this->value = $value;
    }

    public function hasDeclaration()
    {
        return !\is_null($this->declaration);
    }

    public function getDeclaration()
    {
        if ($this->hasDeclaration()) {
            return $this->declaration;
        }

        throw new LogicException('Declaration is not defined for this argument!');
    }

    public function hasValue()
    {
        return !\is_null($this->value);
    }

    public function getValue()
    {
        if ($this->hasValue()) {
            return $this->value;
        }

        throw new LogicException('Value is not defined for this argument!');
    }
}
