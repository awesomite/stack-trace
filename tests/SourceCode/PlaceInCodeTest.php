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

use Awesomite\StackTrace\SourceCode\Lines\LinesInterface;

/**
 * @internal
 */
final class PlaceInCodeTest extends PlaceInCodeProviders
{
    /**
     * @dataProvider providerInvalidLineLimit
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Too big number, cannot be bigger than \d+!$/
     *
     * @param int $limit
     */
    public function testInvalidLineLimit($limit)
    {
        $placeInCode = new PlaceInCode(__FILE__, __LINE__);
        $placeInCode->getAdjacentCode($limit);
    }

    /**
     * @dataProvider providerGetFile
     *
     * @param PlaceInCode $placeInCode
     * @param string      $expectedFile
     * @param int         $expectedLine
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
     * @expectedExceptionMessageRegExp /^File .* does not exist!$/
     *
     * @param PlaceInCode $placeInCode
     */
    public function testInvalidFile(PlaceInCode $placeInCode)
    {
        $placeInCode->getAdjacentCode(1);
    }

    /**
     * @dataProvider                   providerInvalidLine
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
     * @param int         $linesCounter
     * @param int         $firstLineNo
     * @param int         $lastLineNo
     */
    public function testMove(PlaceInCode $placeInCode, $linesCounter, $firstLineNo, $lastLineNo)
    {
        $adjacentCode = $placeInCode->getAdjacentCode($linesCounter);
        $this->assertSame($firstLineNo, $adjacentCode->getFirstLineIndex());
        $this->assertSame($lastLineNo, $adjacentCode->getLastLineIndex());
    }
}
