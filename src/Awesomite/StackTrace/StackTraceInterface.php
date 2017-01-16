<?php

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