<?php

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Arguments\ArgumentInterface;
use Awesomite\StackTrace\Exceptions\InvalidArgumentException;
use Awesomite\StackTrace\Steps\StepInterface;

/**
 * @internal
 */
class StackTraceFactoryTest extends BaseTestCase
{
    /**
     * @dataProvider providerCreate
     *
     * @param int $length
     */
    public function testCreate($length)
    {
        $stackTrace = $this->createFactory()->create($length);
        $this->assertTrue($stackTrace instanceof StackTraceInterface);
        $this->assertLessThanOrEqual($length, count($stackTrace));
    }

    public function providerCreate()
    {
        return array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(500),
        );
    }

    /**
     * @dataProvider providerCreateByException
     *
     * @param \Exception $exception
     * @param int $length
     */
    public function testCreateByException($exception, $length)
    {
        $stackTrace = $this->createFactory()->createByThrowable($exception, $length);
        $this->assertTrue($stackTrace instanceof StackTraceInterface);
        $this->assertLessThanOrEqual($length, count($stackTrace));
    }

    public function providerCreateByException()
    {
        return array(
            array(new \Exception(), 1),
            array(new \Exception(), 2),
            array(new \Exception(), 500),
        );
    }

    /**
     * @dataProvider providerNotThrowable
     *
     * @param $notThrowable
     */
    public function testInvalidArgument($notThrowable)
    {
        $class = new InvalidArgumentException();
        $this->setExpectedException(get_class($class));
        $this->createFactory()->createByThrowable($notThrowable);
    }

    public function providerNotThrowable()
    {
        return array(
            array(new \stdClass()),
            array(array()),
            array(null),
            array(false),
            array(1),
        );
    }

    public function testWithoutArguments()
    {
        $traces = array(
            $this->createFactory()->create(0, true),
            $this->createFactory()->createByThrowable(new \Exception('Test ' . __METHOD__), 0, true),
        );

        $atLeast1 = false;
        foreach ($traces as $trace) {
            /** @var StackTraceInterface $trace */
            foreach ($trace as $step) {
                /** @var StepInterface $step */
                foreach ($step->getArguments() as $argument) {
                    /** @var ArgumentInterface $argument */
                    $this->assertFalse($argument->hasValue());
                    $atLeast1 = true;
                }
            }
        }
        $this->assertTrue($atLeast1);
    }

    private function createFactory()
    {
        return new StackTraceFactory();
    }
}