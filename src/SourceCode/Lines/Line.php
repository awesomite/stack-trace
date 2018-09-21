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

/**
 * @internal
 */
final class Line implements LineInterface
{
    /**
     * @var string
     */
    private $lineValue;

    private $file;

    private $lineNo;

    public function __construct($lineValue, $fileName, $lineNo)
    {
        $this->lineValue = $lineValue;
        $this->file = $fileName;
        $this->lineNo = $lineNo;
    }

    public function __toString()
    {
        return $this->lineValue;
    }

    public function getFileName()
    {
        return $this->file;
    }

    public function getLineNumber()
    {
        return $this->lineNo;
    }
}
