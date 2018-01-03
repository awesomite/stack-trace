<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\SourceCode\Lines;

interface LineInterface
{
    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return int
     */
    public function getLineNumber();

    public function __toString();
}
