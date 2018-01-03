<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Values;

use Awesomite\StackTrace\BaseTestCase;

/**
 * @internal
 */
class ValueTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param Value $value
     * @param       $expectedRealValue
     */
    public function testAll(Value $value, $expectedRealValue)
    {
        $this->assertTrue($value->isRealValueReadable());
        $this->assertSame($expectedRealValue, $value->getRealValue());
        $this->assertSame($value->getDump(), (string)$value);
        $this->expectOutputString($value->getDump());
        $value->dump();
    }

    public function providerAll()
    {
        $values = array(
            new \stdClass(),
            null,
            false,
            1,
            1.5,
            'Hello World!',
        );
        $result = array();

        foreach ($values as $value) {
            $result[] = array(new Value($value), $value);
        }

        $result[] = array(\unserialize(\serialize(new Value(100))), 100);

        return $result;
    }

    /**
     * @dataProvider providerSerialize
     *
     * @param Value $value
     */
    public function testSerialize(Value $value)
    {
        /** @var Value $restored */
        $restored = \unserialize(\serialize($value));
        $this->assertSame($value->getDump(), $restored->getDump());
        $this->assertSame((string)$value, $restored->getDump());
        $this->assertSame($value->getRealValue(), $restored->getRealValue());
    }

    public function providerSerialize()
    {
        return array(
            array(new Value(23)),
            array(new Value(false)),
            array(new Value(null)),
        );
    }
}
