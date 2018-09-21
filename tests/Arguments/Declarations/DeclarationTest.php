<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Declarations;

use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
final class DeclarationTest extends DeclarationProviders
{
    /**
     * @dataProvider providerGetName
     *
     * @param Declaration $declaration
     * @param             $expectedName
     */
    public function testGetName(Declaration $declaration, $expectedName)
    {
        $this->assertSame($expectedName, $declaration->getName());
    }

    /**
     * @dataProvider providerGetType
     *
     * @param Declaration $declaration
     * @param bool        $hasType
     * @param string      $expectedName
     */
    public function testGetType(Declaration $declaration, $hasType, $expectedName = '')
    {
        if (!$hasType) {
            $this->setExpectedException(\get_class(new LogicException()));
        }

        $this->assertSame($hasType, $declaration->hasType());
        $this->assertSame($expectedName, (string)$declaration->getType());
    }

    /**
     * @dataProvider providerIsPassedByReference
     *
     * @param Declaration $declaration
     * @param bool        $isPassedByReference
     */
    public function testIsPassedByReference(Declaration $declaration, $isPassedByReference)
    {
        $this->assertSame($isPassedByReference, $declaration->isPassedByReference());
    }

    /**
     * @dataProvider providerIsVariadic
     *
     * @param Declaration $declaration
     * @param bool        $isVariadic
     */
    public function testIsVariadic(Declaration $declaration, $isVariadic)
    {
        $this->assertSame($isVariadic, $declaration->isVariadic());
    }

    /**
     * @dataProvider providerDefaultValue
     *
     * @param Declaration $declaration
     * @param bool        $hasDefaultValue
     * @param mixed       $defaultValue
     */
    public function testDefaultValue(Declaration $declaration, $hasDefaultValue, $defaultValue = null)
    {
        if (!$hasDefaultValue) {
            $this->setExpectedException(\get_class(new LogicException()));
        }

        $this->assertSame($hasDefaultValue, $declaration->hasDefaultValue());
        $this->assertSame($defaultValue, $declaration->getDefaultValue());
    }

    /**
     * @dataProvider providerDefaultValueConstantName
     *
     * @param Declaration $declaration
     * @param bool        $hasDefault
     * @param string      $defaultName
     */
    public function testDefaultValueConstantName(Declaration $declaration, $hasDefault, $defaultName = '')
    {
        if (!$hasDefault) {
            $this->setExpectedException(\get_class(new LogicException()));
        }

        $this->assertSame($hasDefault, $declaration->hasDefaultValueConstantName());
        $this->assertSame($defaultName, $declaration->getDefaultValueConstantName());
    }
}
