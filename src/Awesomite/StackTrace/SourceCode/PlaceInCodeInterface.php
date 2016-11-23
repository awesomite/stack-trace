<?php

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\SourceCode\Lines\LineInterface;
use Awesomite\StackTrace\SourceCode\Lines\LinesInterface;

interface PlaceInCodeInterface
{
    /**
     * @param int $linesLimit
     * @return LineInterface[]|LinesInterface
     */
    public function getAdjacentCode($linesLimit);

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return int
     */
    public function getLineNumber();
}