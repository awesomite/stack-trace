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
