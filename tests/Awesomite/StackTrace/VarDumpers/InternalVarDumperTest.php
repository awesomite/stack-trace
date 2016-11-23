<?php

namespace Awesomite\StackTrace\VarDumpers;

/**
 * @internal
 */
class InternalVarDumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param mixed $value
     * @param string $dump
     */
    public function testAll($value, $dump)
    {
        $dumper = new InternalVarDumper();
        $this->assertSame($dump, $dumper->getDump($value));
        $this->expectOutputString($dump);
        $dumper->dump($value);
    }

    public function providerAll()
    {
        return array(
            array(1, "int(1)\n"),
            array(false, "bool(false)\n"),
            array(null, "NULL\n"),
        );
    }
}