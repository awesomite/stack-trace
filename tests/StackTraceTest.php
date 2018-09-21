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
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
final class StackTraceTest extends BaseTestCase
{
    public function testAll()
    {
        $debugBacktrace = \debug_backtrace();
        $debugBacktrace = \array_slice($debugBacktrace, 0, 3, true);
        $stackTrace = new StackTrace($debugBacktrace, new LightVarDumper());
        $this->assertSame(\count($debugBacktrace), \count($stackTrace));
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
        $serialized = \serialize($stackTrace);
        $restored = \unserialize($serialized);
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
                    return $argument->getValue()->dumpAsString();
                }
            }
        }

        return null;
    }

    public function providerSerialize()
    {
        $getBackTrace = function () {
            return \debug_backtrace();
        };
        $backTrace = \call_user_func($getBackTrace, function () {
        });
        $factory = new StackTraceFactory();

        return array(
            array(new StackTrace($backTrace, new LightVarDumper())),
            array($factory->create()),
            array(\unserialize(\serialize($factory->create()))),
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
        $this->assertSame(32, \mb_strlen($stackTraceA->getId()));
    }

    public function testToString()
    {
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create();
        $string = (string)$stackTrace;
        $shouldContains = array(
            __FUNCTION__,
            __CLASS__,
        );
        foreach ($shouldContains as $expectedPart) {
            $this->assertContains($expectedPart, $string);
        }
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp #^Cannot use incompatible version to unserialize stack trace \(serialized by\: 999\.0\.0, current\: .*\)\.$#
     */
    public function testCannotUnserialize()
    {
        $string
            = 'C:31:"Awesomite\StackTrace\StackTrace":81:{a:3:{s:5:"steps";a:0:{}s:13:"filesContents";a:0:{}s:9:"__version";s:7:"999.0.0";}}';
        \unserialize($string);
    }

    public function testVariadic()
    {
        if (\version_compare(PHP_VERSION, '5.6') >= 0) {
            $stackTraceVariadic = new StackTraceVariadic($this);
            $stackTraceVariadic->handleTest();
        } else {
            $this->assertTrue(true);
        }
    }

    public function testSemiVariadic()
    {
        $this->handleSemiVariadic(1);
        $this->handleSemiVariadic(1, 2);
        $this->handleSemiVariadic(1, 2, 3);
    }

    private function handleSemiVariadic()
    {
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create(2);
        /** @var StepInterface[] $steps */
        $steps = \iterator_to_array($stackTrace->getIterator());
        $step = $steps[1];
        /** @var ArgumentInterface[] $args */
        $args = \iterator_to_array($step->getArguments());
        $this->assertSame(\count(\func_get_args()), \count($args));
        foreach ($args as $argument) {
            $this->assertTrue($argument->hasValue());
            $this->assertFalse($argument->hasDeclaration());
        }
    }

    /**
     * There is an option to change value of passed argument usign debug_backtrace() function.
     * Class StackTrace should not change any value passed by reference.
     *
     * @see testChangeReference
     */
    public function testDoNotChangeReferences()
    {
        $original = 'original';
        $copy = $original;
        $this->doNotChangeReferences($original);
        $this->assertSame($copy, $original);
    }

    private function doNotChangeReferences(&$reference)
    {
        /**
         * debug_backtrace()[$x]['args'] can contain references
         */
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create(2);
        foreach ($stackTrace as $step) {
        }
    }

    public function testChangeReference()
    {
        /**
         * HHVM does not allow to change value of reference using debug_backtrace()
         */
        if (\defined('HHVM_VERSION')) {
            $this->assertTrue(true);

            return;
        }

        $original = 'original';
        $copy = $original;
        $this->changeReference($original);
        $this->assertNotSame($copy, $original);
    }

    private function changeReference(&$reference)
    {
        $this->modifyArgsInStackTrace();
    }

    private function modifyArgsInStackTrace()
    {
        $stackTrace = \debug_backtrace();
        $stackTrace[1]['args'][0] = 'I\'m a hacker!';
    }
}
