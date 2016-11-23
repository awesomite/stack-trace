<?php

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\Exceptions\InvalidArgumentException;
use Awesomite\StackTrace\Exceptions\LogicException;
use Awesomite\StackTrace\SourceCode\Lines\Line;
use Awesomite\StackTrace\SourceCode\Lines\Lines;

/**
 * @internal
 */
class PlaceInCode implements PlaceInCodeInterface
{
    private $fileName;

    private $lineNo;

    public function __construct($fileName, $lineNo)
    {
        if (!$lineNo) {
            throw new InvalidArgumentException('Line number must be equal at least 1!');
        }
        $this->fileName = $fileName;
        $this->lineNo = $lineNo;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getLineNumber()
    {
        return $this->lineNo;
    }

    public function getAdjacentCode($linesLimit)
    {
        if (!is_file($this->fileName)) {
            throw new LogicException("File {$this->fileName} does not exist!");
        }

        $strings = explode("\n", file_get_contents($this->fileName));
        $count = count($strings);

        if ($this->lineNo > $count) {
            throw new LogicException("Line {$this->lineNo} does not exist in file {$this->fileName}!");
        }

        $firstLine = $this->lineNo - floor($linesLimit/2);
        $lastLine = $this->lineNo  + ceil($linesLimit/2) - 1;

        list($firstMoved, $lastMoved) = $this->moveIfNeed($firstLine, $lastLine, $count);
        $firstLine -= $lastMoved;
        $lastLine += $firstMoved;
        $this->moveIfNeed($firstLine, $lastLine, $count);

        array_unshift($strings, '');
        $strings = array_slice($strings, $firstLine, $lastLine - $firstLine + 1, true);
        $result = array();

        foreach ($strings as $lineNo => $lineVal) {
            $result[$lineNo] = new Line($lineVal, $this->fileName, $lineNo);
        }

        return new Lines($result);
    }

    private function moveIfNeed(&$firstLine, &$lastLine, $count)
    {
        $beginMoved = 0;
        $endMoved = 0;

        if ($firstLine < 1) {
            $beginMoved = 1 - $firstLine;
            $firstLine = 1;
        }

        if ($lastLine > $count) {
            $endMoved = $lastLine - $count;
            $lastLine = $count;
        }

        return array($beginMoved, $endMoved);
    }
}