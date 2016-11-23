<?php

namespace Awesomite\StackTrace\SourceCode\Lines;
use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
class Lines implements LinesInterface
{
    /**
     * @var array|LineInterface[]
     */
    private $lines;

    /**
     * LineIterator constructor.
     * @param LineInterface[] $lines
     */
    public function __construct(array $lines)
    {
        if (empty($lines)) {
            throw new InvalidArgumentException('Lines array cannot be empty!');
        }
        $this->lines = $lines;
    }

    public function getFirstLineIndex()
    {
        return key(array_slice($this->lines, 0, 1, true));
    }

    public function getLastLineIndex()
    {
        return key(array_slice($this->lines, -1, 1, true));
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->lines);
    }

    public function count()
    {
        return count($this->lines);
    }

    public function __toString()
    {
        return implode("\n", $this->lines);
    }
}