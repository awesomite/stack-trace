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
use Awesomite\StackTrace\Types\TypeInterface;

interface DeclarationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isPassedByReference();

    /**
     * @return bool
     */
    public function hasType();

    /**
     * @throws LogicException
     *
     * @return TypeInterface
     */
    public function getType();

    /**
     * @return bool
     */
    public function isVariadic();

    /**
     * @return bool
     */
    public function hasDefaultValue();

    /**
     * @throws LogicException
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return bool
     */
    public function hasDefaultValueConstantName();

    /**
     * @throws LogicException
     *
     * @return string
     */
    public function getDefaultValueConstantName();
}
