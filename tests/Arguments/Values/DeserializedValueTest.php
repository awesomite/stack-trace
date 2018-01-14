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

class DeserializedValueTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param $dump
     */
    public function testAll($dump)
    {
        $value = new DeserializedValue($dump);
        $this->assertSame($dump, (string)$value);
        $this->assertSame($dump, $value->dumpAsString());
        $this->assertSame($dump, $value->getDump());
        $this->assertFalse($value->isRealValueReadable());
        $this->expectOutputString($dump);
        $value->dump();
    }

    public function providerAll()
    {
        return array(
            array('int(1)'),
            array('bool(false)'),
        );
    }

    /**
     * @expectedException \Awesomite\StackTrace\Arguments\Values\CannotRestoreValueException
     * @expectedExceptionMessage Cannot restore value!
     */
    public function testGetRealValue()
    {
        $value = new DeserializedValue('int(1)');
        $value->getRealValue();
    }
}
