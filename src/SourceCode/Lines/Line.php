<?php

namespace Awesomite\StackTrace\SourceCode\Lines;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;

/**
 * @internal
 */
class Line implements LineInterface
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
