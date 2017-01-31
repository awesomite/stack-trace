<?php

namespace Awesomite\StackTrace\SourceCode\Lines;

use Awesomite\StackTrace\BaseTestCase;

/**
 * @internal
 */
class LineTest extends BaseTestCase
{
    /**
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     */
    public function testInvalidConstructor()
    {
        new Line(false, __FILE__, __LINE__);
    }

    /**
     * @dataProvider providerAll
     *
     * @param Line $line
     * @param $expectedFile
     * @param $expectedNumber
     * @param $expectedValue
     */
    public function testAll(Line $line, $expectedFile, $expectedNumber, $expectedValue)
    {
        $this->assertSame($expectedFile, $line->getFileName());
        $this->assertSame($expectedNumber, $line->getLineNumber());
        $this->assertSame($expectedValue, (string) $line);
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
                $value
            );
        }

        return $result;
    }

    private function getLinesOf($fileName)
    {
        $lines = explode("\n", file_get_contents($fileName));
        array_unshift($lines, '');
        unset($lines[0]);

        return $lines;
    }
}