<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Steps;

use Awesomite\StackTrace\Arguments\ArgumentInterface;
use Awesomite\StackTrace\Arguments\ArgumentsInterface;
use Awesomite\StackTrace\Exceptions\LogicException;
use Awesomite\StackTrace\Functions\FunctionInterface;
use Awesomite\StackTrace\SourceCode\PlaceInCodeInterface;

interface StepInterface
{
    /**
     * @return ArgumentInterface[]|ArgumentsInterface
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
