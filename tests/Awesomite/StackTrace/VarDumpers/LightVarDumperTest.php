<?php

namespace Awesomite\StackTrace\VarDumpers;

/**
 * @internal
 */
class LightVarDumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerDump
     *
     * @param mixed $var
     * @param string $expectedDump
     */
    public function testDump($var, $expectedDump)
    {
        $dumper = new LightVarDumper();
        $this->assertSame($expectedDump, $dumper->getDump($var));
    }

    public function providerDump()
    {
        $array = range(1, 21);
        $array[0] = range(1, 5);
        $arrayDump = <<<ARRAY
array(21) {
  [0] => 
  array(5) {
    [0] => 
    int(1)
    [1] => 
    int(2)
    [2] => 
    int(3)
    [3] => 
    int(4)
    [4] => 
    int(5)
  }
  [1] => 
  int(2)
  [2] => 
  int(3)
  [3] => 
  int(4)
  [4] => 
  int(5)
  [5] => 
  int(6)
  [6] => 
  int(7)
  [7] => 
  int(8)
  [8] => 
  int(9)
  [9] => 
  int(10)
  [10] => 
  int(11)
  [11] => 
  int(12)
  [12] => 
  int(13)
  [13] => 
  int(14)
  [14] => 
  int(15)
  [15] => 
  int(16)
  [16] => 
  int(17)
  [17] => 
  int(18)
  [18] => 
  int(19)
  [19] => 
  int(20)
  (...)
}

ARRAY;

        return array(
            array(false, "bool(false)\n"),
            array(150, "int(150)\n"),
            array(new \stdClass(), "object(stdClass) {}\n"),
            array($array, $arrayDump),
            array(null, "NULL\n"),
        );
    }
}