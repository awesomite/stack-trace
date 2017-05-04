<?php

namespace Awesomite\StackTrace\Functions;

use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
class AFunctionTest extends AFunctionProviders
{
    /**
     * @dataProvider providerGetName
     *
     * @param AFunction $function
     * @param $expectedName
     */
    public function testGetName(AFunction $function, $expectedName)
    {
        $this->assertSame($expectedName, $function->getName());
    }

    /**
     * @dataProvider providerIsClosure
     *
     * @param AFunction $function
     * @param bool $isClosure
     */
    public function testIsClosure(AFunction $function, $isClosure)
    {
        $this->assertSame($isClosure, $function->isClosure());
    }

    /**
     * @dataProvider providerInClass
     *
     * @param AFunction $function
     * @param bool $isInClass
     */
    public function testInClass(AFunction $function, $isInClass)
    {
        $this->assertSame($isInClass, $function->isInClass());
    }

    /**
     * @dataProvider providerIsKeyword
     *
     * @param AFunction $function
     * @param bool $isKeyword
     */
    public function testIsKeyword(AFunction $function, $isKeyword)
    {
        $this->assertSame($isKeyword, $function->isKeyword());
    }

    /**
     * @dataProvider providerIsDeprecated
     *
     * @param AFunction $function
     * @param bool $isDeprecated
     */
    public function testIsDeprecated(AFunction $function, $isDeprecated)
    {
        $this->assertSame($isDeprecated, $function->isDeprecated());
    }

    /**
     * @dataProvider providerReflection
     *
     * @param AFunction $function
     * @param bool $hasReflection
     */
    public function testReflection(AFunction $function, $hasReflection)
    {
        if (!$hasReflection) {
            $this->setExpectedException(get_class(new LogicException()));
        }
        $this->assertSame($hasReflection, $function->hasReflection());
        $this->assertTrue($function->getReflection() instanceof \ReflectionFunctionAbstract);
    }
}
