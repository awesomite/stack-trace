<?php

namespace Awesomite\StackTrace;

use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\StackTrace\VarDumpers\LightVarDumper;

/**
 * @internal
 */
class StackTraceTest extends BaseTestCase
{
    public function testAll()
    {
        $debugBacktrace = debug_backtrace();
        $debugBacktrace = array_slice($debugBacktrace, 0, 3, true);
        $stackTrace = new StackTrace($debugBacktrace);
        $this->assertSame(count($debugBacktrace), count($stackTrace));
        $this->assertTrue($stackTrace->getIterator() instanceof \Traversable);

        foreach ($stackTrace->getIterator() as $step) {
            $this->assertTrue($step instanceof StepInterface);
        }

        $stackTrace->setVarDumper(new LightVarDumper());
        foreach ($stackTrace->getIterator() as $step) {
            $this->assertTrue($step instanceof StepInterface);
        }
    }

    /**
     * @dataProvider providerSerialize
     *
     * @param StackTrace $stackTrace
     */
    public function testSerialize(StackTrace $stackTrace)
    {
        $serialized = serialize($stackTrace);
        $restored = unserialize($serialized);
        $this->assertTrue($restored instanceof StackTrace);
        $firstDump = $this->getFirstDump($stackTrace);
        $restoredFirstDump = $this->getFirstDump($restored);
        $this->assertSame($firstDump, $restoredFirstDump);
        $this->assertNotNull($firstDump);
    }

    private function getFirstDump(StackTrace $stackTrace)
    {
        foreach ($stackTrace as $step) {
            /** @var StepInterface $step */
            foreach ($step->getArguments() as $argument) {
                if ($argument->hasValue()) {
                    return $argument->getValue()->getDump();
                }
            }
        }

        return null;
    }

    public function providerSerialize()
    {
        $getBackTrace = function () {
            return debug_backtrace();
        };
        $backTrace = call_user_func($getBackTrace, function () {});
        $factory = new StackTraceFactory();

        return array(
            array(new StackTrace($backTrace)),
            array($factory->create()),
            array(unserialize(serialize($factory->create()))),
        );
    }

    public function testGetId()
    {
        $factory = new StackTraceFactory();
        $stackTraceA = $factory->create();
        $stackTraceB = $factory->create(); $stackTraceC = $factory->create();
        $this->assertNotEquals($stackTraceA->getId(), $stackTraceB->getId());
        $this->assertSame($stackTraceB->getId(), $stackTraceC->getId());
        $this->assertInternalType('string', $stackTraceA->getId());
        $this->assertSame(32, strlen($stackTraceA->getId()));
    }

    public function testToString()
    {
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create();
        $string = (string) $stackTrace;
        $shouldContains = array(
            __FUNCTION__,
            __CLASS__,
        );
        foreach ($shouldContains as $expectedPart) {
            $this->assertContains($expectedPart, $string);
        }
    }
}
