<?php

namespace Awesomite\StackTrace\Functions;

use Awesomite\StackTrace\Exceptions\LogicException;

interface FunctionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isInClass();

    /**
     * @return bool
     */
    public function isClosure();

    /**
     * @return bool
     */
    public function isKeyword();

    /**
     * @throws LogicException
     *
     * @return \ReflectionFunctionAbstract
     */
    public function getReflection();

    /**
     * @return bool
     */
    public function hasReflection();

    /**
     * @return bool
     */
    public function isDeprecated();
}