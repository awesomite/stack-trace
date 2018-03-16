<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Functions;

use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
class AFunction implements FunctionInterface
{
    private $reflection = null;

    private $arrayStep;

    public function __construct(array $step)
    {
        $this->arrayStep = $step;
    }

    public function getName()
    {
        $result = '';

        if ($this->isInClass()) {
            $result .= $this->arrayStep['class'];
        }

        if (isset($this->arrayStep['type'])) {
            $result .= $this->arrayStep['type'];
        }

        return $result . $this->arrayStep['function'];
    }

    public function isClosure()
    {
        // @codeCoverageIgnoreStart
        if (\defined('HHVM_VERSION') && 0 === \strpos($this->arrayStep['function'], 'Closure$')) {
            return true;
        }
        // @codeCoverageIgnoreEnd

        return false !== \strpos($this->arrayStep['function'], '{closure}');
    }

    public function isInClass()
    {
        return isset($this->arrayStep['class']);
    }

    public function isKeyword()
    {
        return !$this->isClosure() && !$this->isInClass() && !\function_exists($this->arrayStep['function']);
    }

    public function isDeprecated()
    {
        if (!$this->hasReflection()) {
            return false;
        }

        $reflection = $this->getReflection();

        if ($reflection->isDeprecated()) {
            return true;
        }

        if ($this->hasDeprecatedTag($reflection->getDocComment())) {
            return true;
        }

        if ($reflection instanceof \ReflectionMethod) {
            try {
                if ($this->hasDeprecatedTag($reflection->getPrototype()->getDocComment())) {
                    return true;
                }
            } catch (\ReflectionException $exception) {
            }

            if ($this->hasDeprecatedTag($reflection->getDeclaringClass()->getDocComment())) {
                return true;
            }
        }

        return false;
    }

    public function getReflection()
    {
        if (\is_null($this->reflection)) {
            $this->reflection = $this->createReflection();
        }

        return $this->reflection;
    }

    public function hasReflection()
    {
        return !$this->isClosure() && !$this->isKeyword()
            && (!$this->isInClass() || \method_exists($this->arrayStep['class'], $this->arrayStep['function']));
    }

    private function hasDeprecatedTag($doc)
    {
        return false !== $doc && \preg_match('#^\s+\*\s+@deprecated($|(\s.*?$))#m', $doc);
    }

    private function createReflection()
    {
        if (!$this->hasReflection()) {
            throw new LogicException("There is no reflection for function {$this->getName()}!");
        }

        if ($this->isInClass()) {
            $class = new \ReflectionClass($this->arrayStep['class']);

            return $class->getMethod($this->arrayStep['function']);
        }

        return new \ReflectionFunction($this->arrayStep['function']);
    }
}
