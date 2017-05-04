<?php

namespace Awesomite\StackTrace\Arguments\Values;

use Awesomite\StackTrace\BaseTestCase;

class MultipleValuesTest extends BaseTestCase
{
    /**
     * @dataProvider providerObject
     *
     * @param MultipleValues $value
     */
    public function testIsRealValueReadable(MultipleValues $value)
    {
        $this->assertSame(false, $value->isRealValueReadable());
    }

    /**
     * @dataProvider providerObject
     *
     * @expectedException \Awesomite\StackTrace\Arguments\Values\CannotRestoreValueException
     * @expectedExceptionMessage Cannot restore value!
     *
     * @param MultipleValues $multipleValues
     */
    public function testGetRealValue(MultipleValues $multipleValues)
    {
        $multipleValues->getRealValue();
    }

    /**
     * @dataProvider providerObject
     *
     * @param MultipleValues $value
     */
    public function testToString(MultipleValues $value)
    {
        $this->assertSame($value->__toString(), $value->getDump());
    }

    /**
     * @dataProvider providerObject
     *
     * @param MultipleValues $value
     */
    public function testDump(MultipleValues $value)
    {
        $this->expectOutputString($value->getDump());
        $value->dump();
    }

    /**
     * @dataProvider providerObject
     *
     * @param MultipleValues $value
     * @param string $expectedDump
     */
    public function testGetDump(MultipleValues $value, $expectedDump)
    {
        $this->assertSame($expectedDump, $value->getDump());
    }

    public function providerObject()
    {
        $subData = range(0, 11);
        $subDataArg = array();
        foreach ($subData as $value) {
            $subDataArg[] = new Value($value);
        }
        array_unshift(
            $subDataArg,
            new MultipleValues(
                array(new Value(5))
            )
        );
        $subValue = new MultipleValues($subDataArg, 5);

        $multipleValue = new MultipleValues(array(
            new Value(1),
            new Value(false),
            new Value(array()),
            $subValue,
        ));
        $expectedDump = <<<DUMP
array(4) {
  [0] => 
  int(1)
  [1] => 
  bool(false)
  [2] => 
  array(0) {
  }
  [3] => 
  array(13) {
    [0] => 
    array(1) {
      [0] => 
      int(5)
    }
    [1] => 
    int(0)
    [2] => 
    int(1)
    [3] => 
    int(2)
    [4] => 
    int(3)
    (...)
  }
}

DUMP;

        return array(
            array($multipleValue, $expectedDump),
        );
    }
}
