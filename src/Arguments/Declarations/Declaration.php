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
use Awesomite\StackTrace\Types\Type;

/**
 * @internal
 */
class Declaration implements DeclarationInterface
{
    const REGEX_CLASS_NOT_EXISTS = '/^Class (?<name>.*) does not exist$/';

    private $parameter;

    /**
     * @codeCoverageIgnore
     *
     * @param \ReflectionParameter $parameter
     */
    public function __construct(\ReflectionParameter $parameter)
    {
        $this->parameter = $parameter;
    }

    public function getName()
    {
        return $this->parameter->getName();
    }

    public function getType()
    {
        if (\version_compare(PHP_VERSION, '7.0') >= 0 && $this->parameter->hasType()) {
            return new Type((string)$this->parameter->getType());
        }

        if (\version_compare(PHP_VERSION, '5.4') >= 0 && $this->parameter->isCallable()) {
            return new Type('callable');
        }

        if ($this->parameter->isArray()) {
            return new Type('array');
        }

        try {
            $class = $this->parameter->getClass();
            if (!\is_null($class)) {
                return new Type($class->getName());
            }
        } catch (\ReflectionException $exception) {
            if (\preg_match(static::REGEX_CLASS_NOT_EXISTS, $exception->getMessage(), $matches)) {
                return new Type($matches['name']);
            }
        }

        throw new LogicException('Type is not defined for this parameter!');
    }

    public function hasType()
    {
        if (\version_compare(PHP_VERSION, '7.0') >= 0 && $this->parameter->hasType()) {
            return true;
        }

        if (\version_compare(PHP_VERSION, '5.4') >= 0 && $this->parameter->isCallable()) {
            return true;
        }

        if ($this->parameter->isArray()) {
            return true;
        }

        try {
            $class = $this->parameter->getClass();
            if (!\is_null($class)) {
                return true;
            }
        } catch (\ReflectionException $exception) {
            if (\preg_match(static::REGEX_CLASS_NOT_EXISTS, $exception->getMessage())) {
                return true;
            }
        }

        return false;
    }

    public function isPassedByReference()
    {
        return $this->parameter->isPassedByReference();
    }

    public function isVariadic()
    {
        return \version_compare(PHP_VERSION, '5.6') >= 0 && $this->parameter->isVariadic();
    }

    public function hasDefaultValue()
    {
        return $this->parameter->isDefaultValueAvailable();
    }

    public function getDefaultValue()
    {
        if ($this->hasDefaultValue()) {
            return $this->parameter->getDefaultValue();
        }

        throw new LogicException('Default value is not defined for this parameter!');
    }

    public function hasDefaultValueConstantName()
    {
        if (\defined('HHVM_VERSION')) {
            // @codeCoverageIgnoreStart
            if (\method_exists($this->parameter, 'isDefaultValueConstant')) {
                return (bool)$this->parameter->isDefaultValueConstant();
            }

            return false;
            // @codeCoverageIgnoreEnd
        }

        return \version_compare(PHP_VERSION, '5.4.6') >= 0
            && $this->hasDefaultValue()
            && $this->parameter->isDefaultValueConstant();
    }

    public function getDefaultValueConstantName()
    {
        if ($this->hasDefaultValueConstantName()) {
            return $this->parameter->getDefaultValueConstantName();
        }

        throw new LogicException('Default value constant is not defined for this parameter!');
    }
}
