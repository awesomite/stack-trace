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

use Awesomite\StackTrace\Arguments\Declarations\Declaration;
use Awesomite\StackTrace\Arguments\Declarations\DeclarationInterface;
use Awesomite\StackTrace\Arguments\Values\Value;
use Awesomite\StackTrace\Arguments\Values\ValueInterface;
use Awesomite\StackTrace\BaseTestCase;
use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
final class ArgumentTest extends BaseTestCase
{
    /**
     * @dataProvider providerDeclaration
     *
     * @param Argument                  $argument
     * @param null|DeclarationInterface $declaration
     */
    public function testDeclaration(Argument $argument, DeclarationInterface $declaration = null)
    {
        if (\is_null($declaration)) {
            $this->setExpectedException(\get_class(new LogicException()));
        }

        $this->assertSame(!\is_null($declaration), $argument->hasDeclaration());
        $this->assertSame($declaration, $argument->getDeclaration());
    }

    public function providerDeclaration()
    {
        $class = new \ReflectionClass($this);
        list($parameter) = $class->getMethod('testDeclaration')->getParameters();
        $declaration = new Declaration($parameter);

        return array(
            array(new Argument($declaration), $declaration),
            array(new Argument()),
        );
    }

    /**
     * @dataProvider providerValue
     *
     * @param Argument            $argument
     * @param null|ValueInterface $value
     */
    public function testValue(Argument $argument, ValueInterface $value = null)
    {
        if (\is_null($value)) {
            $this->setExpectedException(\get_class(new LogicException()));
        }

        $this->assertSame(!\is_null($value), $argument->hasValue());
        $this->assertSame($value, $argument->getValue());
    }

    public function providerValue()
    {
        $value = new Value($this);

        return array(
            array(new Argument(null, $value), $value),
            array(new Argument()),
        );
    }
}
