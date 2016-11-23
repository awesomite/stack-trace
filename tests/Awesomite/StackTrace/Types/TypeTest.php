<?php

namespace Awesomite\Types;

use Awesomite\StackTrace\Types\Type;

/**
 * @internal
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerToString
     *
     * @param Type $type
     * @param string $expectedName
     * @param bool $shouldBeTheSame
     */
    public function testToString(Type $type, $expectedName, $shouldBeTheSame)
    {
        $this->assertEquals($shouldBeTheSame, $expectedName === (string) $type);
    }

    public function providerToString()
    {
        return array(
            array(new Type('array'), 'array', true),
            array(new Type('closure'), 'closure', true),
            array(new Type('array'), 'closure', false),
        );
    }

    public function testConstructor()
    {
        $stringType = gettype(array());
        $type = new Type($stringType);
        $this->assertSame($stringType, (string) $type);
    }
}