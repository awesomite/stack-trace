<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\VarDumperInterface;

interface StackTraceInterface extends \IteratorAggregate, \Countable, \Serializable
{
    /**
     * @return StepInterface[]|\Traversable
     */
    public function getIterator();

    public function __toString();

    /**
     * Returns unique value calculated by md5 based on files and lines from stack trace.
     *
     * @return string
     */
    public function getId();

    public function setVarDumper(VarDumperInterface $varDumper);
}
