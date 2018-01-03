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
use Awesomite\StackTrace\Arguments\Values\ValueInterface;
use Awesomite\StackTrace\Functions\FunctionInterface;

/**
 * @internal
 */
class Arguments implements ArgumentsInterface
{
    /**
     * @var FunctionInterface
     */
    private $function;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @codeCoverageIgnore
     *
     * @param ValueInterface[]       $arguments
     * @param FunctionInterface|null $function
     */
    public function __construct(array $arguments, FunctionInterface $function = null)
    {
        $this->arguments = $arguments;
        $this->function = $function;
    }

    public function getIterator()
    {
        $result = array();
        $arguments = $this->arguments;
        if ($this->function && $this->function->hasReflection()) {
            foreach ($this->function->getReflection()->getParameters() as $parameter) {
                $declaration = new Declaration($parameter);

                if ($arguments) {
                    $result[] = new Argument($declaration, \array_shift($arguments));
                    continue;
                }

                $result[] = new Argument(new Declaration($parameter));
            }
        }

        foreach ($arguments as $argument) {
            $result[] = new Argument(null, $argument);
        }

        return new \ArrayIterator($result);
    }

    public function count()
    {
        if ($this->function && $this->function->hasReflection()) {
            $parameters = $this->function->getReflection()->getParameters();

            if (\version_compare(PHP_VERSION, '5.6') >= 0) {
                /** @var \ReflectionParameter|bool $parameter */
                $parameter = \end($parameters);
                if ($parameter && $parameter->isVariadic()) {
                    return \count($parameters);
                }
            }

            return \max(\count($parameters), \count($this->arguments));
        }

        return \count($this->arguments);
    }
}
