<?php

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
