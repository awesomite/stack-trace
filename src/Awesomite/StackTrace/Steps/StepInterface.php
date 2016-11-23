<?php

namespace Awesomite\StackTrace\Steps;

use Awesomite\StackTrace\Arguments\ArgumentInterface;
use Awesomite\StackTrace\Arguments\ArgumentsInterface;
use Awesomite\StackTrace\Functions\FunctionInterface;
use Awesomite\StackTrace\SourceCode\PlaceInCodeInterface;
use Awesomite\StackTrace\Exceptions\LogicException;

interface StepInterface
{
    /**
     * @return ArgumentsInterface|ArgumentInterface[]
     */
    public function getArguments();

    /**
     * @throws LogicException
     *
     * @return PlaceInCodeInterface
     */
    public function getPlaceInCode();

    /**
     * @return bool
     */
    public function hasPlaceInCode();

    /**
     * @return bool
     */
    public function hasCalledFunction();

    /**
     * @return FunctionInterface
     */
    public function getCalledFunction();
}