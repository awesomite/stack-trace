<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Types;

use Awesomite\StackTrace\BaseTestCase;

/**
 * @internal
 */
final class TypeTest extends BaseTestCase
{
    /**
     * @dataProvider providerToString
     *
     * @param Type   $type
     * @param string $expectedName
     * @param bool   $shouldBeTheSame
     */
    public function testToString(Type $type, $expectedName, $shouldBeTheSame)
    {
        $this->assertEquals($shouldBeTheSame, $expectedName === (string)$type);
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
        $stringType = \gettype(array());
        $type = new Type($stringType);
        $this->assertSame($stringType, (string)$type);
    }
}
