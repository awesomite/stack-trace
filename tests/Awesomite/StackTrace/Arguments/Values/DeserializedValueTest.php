<?php

namespace Awesomite\StackTrace\Arguments\Values;

class DeserializedValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param $dump
     */
    public function testAll($dump)
    {
        $value = new DeserializedValue($dump);
        $this->assertSame($dump, (string) $value);
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