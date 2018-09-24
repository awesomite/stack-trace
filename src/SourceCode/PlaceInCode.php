<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\Constants;
use Awesomite\StackTrace\Exceptions\InvalidArgumentException;
use Awesomite\StackTrace\Exceptions\LogicException;
use Awesomite\StackTrace\SourceCode\Lines\Line;
use Awesomite\StackTrace\SourceCode\Lines\Lines;

/**
 * @internal
 */
final class PlaceInCode implements PlaceInCodeInterface
{
    private $fileName;

    private $lineNo;

    private $file;

    /**
     * @param string             $fileName
     * @param int                $lineNo
     * @param null|FileInterface $file
     */
    public function __construct($fileName, $lineNo, FileInterface $file = null)
    {
        if (!$lineNo) {
            throw new InvalidArgumentException('Line number must be equal at least 1!');
        }
        $this->fileName = $fileName;
        $this->lineNo = $lineNo;
        $this->file = $file;
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
        $maxValue = Constants::MAX_LINE_THRESHOLD * 2;
        if ($linesLimit > $maxValue) {
            throw new InvalidArgumentException("Too big number, cannot be bigger than {$maxValue}!");
        }

        if (\is_null($this->file) && !\is_file($this->fileName)) {
            throw new LogicException("File {$this->fileName} does not exist!");
        }

        $file = $this->getFile();
        $count = $file->countLines();

        if ($this->lineNo > $count) {
            throw new LogicException("Line {$this->lineNo} does not exist in file {$this->fileName}!");
        }

        $firstLine = $this->lineNo - \floor($linesLimit / 2);
        $lastLine = $this->lineNo + \ceil($linesLimit / 2) - 1;

        list($firstMoved, $lastMoved) = $this->moveIfNeed($firstLine, $lastLine, $count);
        $firstLine -= $lastMoved;
        $lastLine += $firstMoved;
        $this->moveIfNeed($firstLine, $lastLine, $count);

        $rawLines = $file->getLines($firstLine, $lastLine);
        $lines = array();
        foreach ($rawLines as $key => $value) {
            $lines[$key] = new Line($value, $file->getFileName(), $key);
        }

        return new Lines($lines);
    }

    private function getFile()
    {
        if (\is_null($this->file)) {
            $this->file = new File($this->fileName);
        }

        return $this->file;
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
