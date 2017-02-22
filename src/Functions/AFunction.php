<?php

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

        if (isset($this->arrayStep['function'])) {
            $result .= $this->arrayStep['function'];
        }

        return $result;
    }

    public function isClosure()
    {
        return strpos($this->arrayStep['function'], '{closure}') !== false;
    }

    public function isInClass()
    {
        return isset($this->arrayStep['class']);
    }

    public function isKeyword()
    {
        return !$this->isClosure() && !$this->isInClass() && !function_exists($this->arrayStep['function']);
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

        if ($this->hasDeprecatedTag($reflection)) {
            return true;
        }

        if ($reflection instanceof \ReflectionMethod) {
            try {
                if ($this->hasDeprecatedTag($reflection->getPrototype())) {
                    return true;
                }
            } catch (\ReflectionException $exception) {}
        }

        return false;
    }

    public function getReflection()
    {
        if (is_null($this->reflection)) {
            $this->reflection = $this->createReflection();
        }

        return $this->reflection;
    }

    public function hasReflection()
    {
        return !$this->isClosure() && !$this->isKeyword()
            && (!$this->isInClass() || method_exists($this->arrayStep['class'], $this->arrayStep['function']));
    }

    private function hasDeprecatedTag(\ReflectionFunctionAbstract $function)
    {
        $comment = $function->getDocComment();

        return $comment !== false && strpos($comment, '@deprecated') !== false;
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
