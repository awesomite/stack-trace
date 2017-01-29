<?php

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\SourceCode\Lines\LineInterface;
use Awesomite\StackTrace\SourceCode\Lines\LinesInterface;

interface PlaceInCodeInterface
{
    /**
     * @param int $linesLimit Max value = \Awesomite\StackTrace\Constants::MAX_LINE_THRESHOLD * 2
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