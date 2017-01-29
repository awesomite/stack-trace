<?php

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\SourceCode\Lines\LinesInterface;

/**
 * @internal
 */
class PlaceInCodeTest extends PlaceInCodeProviders
{
    /**
     * @dataProvider providerGetFile
     *
     * @param PlaceInCode $placeInCode
     * @param string $expectedFile
     * @param int $expectedLine
     */
    public function testGetFileLine(PlaceInCode $placeInCode, $expectedFile, $expectedLine)
    {
        $this->assertSame($expectedFile, $placeInCode->getFileName());
        $this->assertSame($expectedLine, $placeInCode->getLineNumber());
    }

    /**
     * @dataProvider providerGetAdjacentCode
     *
     * @param PlaceInCode $placeInCode
     */
    public function testGetAdjacentCode(PlaceInCode $placeInCode)
    {
        $this->assertTrue($placeInCode->getAdjacentCode(1) instanceof LinesInterface);
    }

    /**
     * @dataProvider providerInvalidFile
     * @expectedException \Awesomite\StackTrace\Exceptions\LogicException
     * @expectedExceptionMessageRegExp /File .* does not exist!/
     *
     * @param PlaceInCode $placeInCode
     */
    public function testInvalidFile(PlaceInCode $placeInCode)
    {
        $placeInCode->getAdjacentCode(1);
    }

    /**
     * @dataProvider providerInvalidLine
     * @expectedException \Awesomite\StackTrace\Exceptions\LogicException
     * @expectedExceptionMessageRegExp /Line .* does not exist in file .*!/
     *
     * @param PlaceInCode $placeInCode
     */
    public function testInvalidLine(PlaceInCode $placeInCode)
    {
        $placeInCode->getAdjacentCode(1);
    }

    /**
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Line number must be equal at least 1!
     */
    public function testInvalidLineConstructor()
    {
        new PlaceInCode(__FILE__, 0);
    }

    /**
     * @dataProvider providerMove
     *
     * @param PlaceInCode $placeInCode
     * @param int $linesCounter
     * @param int $firstLineNo
     * @param int $lastLineNo
     */
    public function testMove(PlaceInCode $placeInCode, $linesCounter, $firstLineNo, $lastLineNo)
    {
        $adjacentCode = $placeInCode->getAdjacentCode($linesCounter);
        $this->assertSame($firstLineNo, $adjacentCode->getFirstLineIndex());
        $this->assertSame($lastLineNo, $adjacentCode->getLastLineIndex());
    }
}