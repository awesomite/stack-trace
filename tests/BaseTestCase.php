<?php

namespace Awesomite\StackTrace;

/**
 * @internal
 */
class BaseTestCase extends BridgeTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }

    /**
     * @param string $exception
     * @param string|null $message
     * @param int|null $code
     */
    public function setExpectedException($exception, $message = '', $code = null)
    {
        $reflection = new \ReflectionClass('Awesomite\StackTrace\BaseTestCase');
        $parentReflection = $reflection->getParentClass();
        if ($parentReflection->hasMethod('setExpectedException')) {
            parent::setExpectedException($exception, $message, $code);
            return;
        }

        $this->expectException($exception);

        if ($message !== null && $message !== '') {
            $this->expectExceptionMessage($message);
        }

        if ($code !== null) {
            $this->expectExceptionCode($code);
        }
    }
}
