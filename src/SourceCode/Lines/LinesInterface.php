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

interface LinesInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return int
     */
    public function getFirstLineIndex();

    /**
     * @return int
     */
    public function getLastLineIndex();

    /**
     * @return LineInterface[]|\Traversable
     */
    public function getIterator();

    public function __toString();
}
