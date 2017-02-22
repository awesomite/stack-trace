<?php

namespace Awesomite\StackTrace\Arguments;

use Awesomite\StackTrace\SourceCode\Lines\LineInterface;

interface ArgumentsInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return LineInterface[]|\Traversable
     */
    public function getIterator();
}
