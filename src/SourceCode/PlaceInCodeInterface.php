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

use Awesomite\StackTrace\SourceCode\Lines\LineInterface;
use Awesomite\StackTrace\SourceCode\Lines\LinesInterface;

interface PlaceInCodeInterface
{
    /**
     * @param int $linesLimit Max value = \Awesomite\StackTrace\Constants::MAX_LINE_THRESHOLD * 2
     *
     * @return LineInterface[]|LinesInterface
     */
    public function getAdjacentCode($linesLimit);

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return int
     */
    public function getLineNumber();
}
