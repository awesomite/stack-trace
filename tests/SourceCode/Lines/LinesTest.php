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
use Awesomite\StackTrace\SourceCode\PlaceInCode;

/**
 * @internal
 */
final class LinesTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param Lines $lines
     * @param       $expectedFirstIndex
     * @param       $expectedLastIndex
     * @param       $expectedCount
     */
    public function testAll(Lines $lines, $expectedFirstIndex, $expectedLastIndex, $expectedCount)
    {
        $this->assertSame($expectedFirstIndex, $lines->getFirstLineIndex());
        $this->assertSame($expectedLastIndex, $lines->getLastLineIndex());
        $this->assertSame($expectedCount, \count($lines));

        $realFirstIndex = null;
        $realLastIndex = null;

        foreach ($lines as $line) {
            /** @var LineInterface $line */
            if (null === $realFirstIndex) {
                $realFirstIndex = $line->getLineNumber();
            }
            $realLastIndex = $line->getLineNumber();
            $this->assertTrue($line instanceof LineInterface);
        }

        $this->assertSame($expectedFirstIndex, $realFirstIndex);
        $this->assertSame($expectedLastIndex, $realLastIndex);
    }

    public function providerAll()
    {
        $fileName = __FILE__;
        $lineNumber = __LINE__;
        $placeInCode = new PlaceInCode($fileName, $lineNumber);

        return array(
            array($placeInCode->getAdjacentCode(3), $lineNumber - 1, $lineNumber + 1, 3),
        );
    }

    /**
     * @dataProvider             providerToString
     *
     * @expectedException \Awesomite\StackTrace\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Lines array cannot be empty!
     */
    public function testEmpty()
    {
        new Lines(array());
    }

    /**
     * @dataProvider providerToString
     *
     * @param Lines $lines
     * @param       $expectedString
     */
    public function testToString(Lines $lines, $expectedString)
    {
        $this->assertSame($expectedString, (string)$lines);
    }

    public function providerToString()
    {
        $fileName = '/tmp/foo/bar';
        $lines = array(
            new Line('<?php', $fileName, 1),
            new Line('', $fileName, 2),
            new Line('namespace foo\bar;', $fileName, 3),
        );
        $expected
            = <<<'SOURCE'
<?php

namespace foo\bar;
SOURCE;

        return array(
            array(new Lines($lines), $expected),
        );
    }
}
