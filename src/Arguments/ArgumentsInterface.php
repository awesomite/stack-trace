<?php

namespace Awesomite\StackTrace\Arguments;

interface ArgumentsInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return ArgumentInterface[]|\Traversable
     */
    public function getIterator();
}
