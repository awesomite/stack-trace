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

use Awesomite\StackTrace\BaseTestCase;

/**
 * @internal
 */
final class LineTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param Line $line
     * @param      $expectedFile
     * @param      $expectedNumber
     * @param      $expectedValue
     */
    public function testAll(Line $line, $expectedFile, $expectedNumber, $expectedValue)
    {
        $this->assertSame($expectedFile, $line->getFileName());
        $this->assertSame($expectedNumber, $line->getLineNumber());
        $this->assertSame($expectedValue, (string)$line);
    }

    public function providerAll()
    {
        $result = array();
        $fileName = __FILE__;
        foreach ($this->getLinesOf($fileName) as $number => $value) {
            $result[] = array(
                new Line($value, $fileName, $number),
                $fileName,
                $number,
                $value,
            );
        }

        return $result;
    }

    private function getLinesOf($fileName)
    {
        $lines = \explode("\n", \file_get_contents($fileName));
        \array_unshift($lines, '');
        unset($lines[0]);

        return $lines;
    }
}
