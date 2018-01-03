<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\SourceCode;

/**
 * @internal
 */
interface FileInterface extends \Serializable
{
    /**
     * @return int
     */
    public function countLines();

    /**
     * @param int $from
     * @param int $to
     *
     * @return string[]
     */
    public function getLines($from, $to);

    /**
     * @return string
     */
    public function getFileName();
}
