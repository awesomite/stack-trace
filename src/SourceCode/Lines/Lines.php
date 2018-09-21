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

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
final class Lines implements LinesInterface
{
    /**
     * @var array|LineInterface[]
     */
    private $lines;

    /**
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
        return \key(\array_slice($this->lines, 0, 1, true));
    }

    public function getLastLineIndex()
    {
        return \key(\array_slice($this->lines, -1, 1, true));
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->lines);
    }

    public function count()
    {
        return \count($this->lines);
    }

    public function __toString()
    {
        return \implode("\n", $this->lines);
    }
}
