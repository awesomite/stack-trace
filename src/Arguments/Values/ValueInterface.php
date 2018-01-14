<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Values;

interface ValueInterface
{
    /**
     * @throws CannotRestoreValueException
     *
     * @return mixed
     */
    public function getRealValue();

    /**
     * @return bool
     */
    public function isRealValueReadable();

    public function dump();

    /**
     * @return string
     */
    public function dumpAsString();

    public function __toString();
}
