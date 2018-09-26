<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Arguments\ArgumentInterface;
use Awesomite\StackTrace\Exceptions\InvalidArgumentException;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\VarDumperInterface;

/**
 * @internal
 */
final class StackTraceFactoryTest extends BaseTestCase
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
        $this->assertLessThanOrEqual($length, \count($stackTrace));
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
     * @param int        $length
     */
    public function testCreateByException($exception, $length)
    {
        $stackTrace = $this->createFactory()->createByThrowable($exception, $length);
        $this->assertTrue($stackTrace instanceof StackTraceInterface);
        $this->assertLessThanOrEqual($length, \count($stackTrace));
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
        $this->setExpectedException(\get_class($class));
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

    /**
     * @dataProvider providerStackTrace
     *
     * @param StackTraceInterface $stackTrace
     * @param array               $data
     */
    public function testStackTrace(StackTraceInterface $stackTrace, array $data)
    {
        foreach ($stackTrace as $step) {
            $row = \array_shift($data);

            if (false === $row) {
                continue;
            }

            if (isset($row['file'])) {
                $this->assertSame($row['file'], $step->getPlaceInCode()->getFileName());
                $this->assertSame($row['line'], $step->getPlaceInCode()->getLineNumber());
            }

            if (isset($row['function'])) {
                $fn = $step->hasCalledFunction()
                    ? $step->getCalledFunction()->getName()
                    : false;
                $this->assertSame($row['function'], $fn);
            }

            if (empty($data)) {
                return;
            }
        }
    }

    public function providerStackTrace()
    {
        $result = array();
        $factory = $this->createFactory();

        $result[] = array(
            $factory->create(2),
            array(
                false,
                array('function' => __CLASS__ . '->' . __FUNCTION__),
            ),
        );

        try {
            eval('throw new \LogicException("Test exception");');
        } catch (\LogicException $exception) {
            $result[] = array(
                $factory->createByThrowable($exception, 2),
                array(
                    array('function' => 'eval', 'file' => __FILE__, 'line' => __LINE__ - 5),
                ),
            );
        }

        $eval
            = <<<EVAL
if (!function_exists('awesomite_test_function')) {
    function awesomite_test_function () {
        throw new \LogicException('Test exception 2');
    }
}
EVAL;
        eval($eval);

        try {
            awesomite_test_function();
        } catch (\LogicException $exception) {
            $result[] = array(
                $factory->createByThrowable($exception, 2),
                array(
                    array('function' => 'eval', 'file' => __FILE__, 'line' => __LINE__ - 8),
                ),
            );
        }

        return $result;
    }

    /**
     * @dataProvider providerConstructor
     *
     * @param null|VarDumperInterface $varDumper
     * @param null|int                $maxSerializableStringLen
     */
    public function testConstructor(VarDumperInterface $varDumper = null, $maxSerializableStringLen = null)
    {
        $stackTraceFactory = new StackTraceFactory($varDumper, $maxSerializableStringLen);
        $reflectionClass = new \ReflectionClass($stackTraceFactory);

        $reflectionVarDumper = $reflectionClass->getProperty('varDumper');
        $reflectionVarDumper->setAccessible(true);

        $reflectionStringLen = $reflectionClass->getProperty('maxSerializableStringLen');
        $reflectionStringLen->setAccessible(true);

        if (null === $varDumper) {
            $this->assertInstanceOf(
                'Awesomite\VarDumper\VarDumperInterface',
                $reflectionVarDumper->getValue($stackTraceFactory)
            );
        } else {
            $this->assertSame($varDumper, $reflectionVarDumper->getValue($stackTraceFactory));
        }

        $this->assertSame($maxSerializableStringLen, $maxSerializableStringLen);
    }

    public function providerConstructor()
    {
        return array(
            array(null, null),
            array(new LightVarDumper(), null),
            array(new LightVarDumper(), 5),
            array(new LightVarDumper(), 6),
            array(null, 5),
            array(null, 6),
        );
    }

    private function createFactory()
    {
        return new StackTraceFactory();
    }
}
