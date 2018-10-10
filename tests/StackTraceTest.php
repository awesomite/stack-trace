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
        $stackTrace = new StackTrace($debugBacktrace, new LightVarDumper(), false);
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
            array(new StackTrace($backTrace, new LightVarDumper(), false)),
            array($factory->create()),
            array(\unserialize(\serialize($factory->create()))),
        );
    }

    public function testGetId()
    {
        $factory = new StackTraceFactory();
        $stackTraceA = $factory->create();
        list($stackTraceB, $stackTraceC) = array($factory->create(), $factory->create());
        $this->assertNotEquals($stackTraceA->getId(), $stackTraceB->getId());
        $this->assertSame($stackTraceB->getId(), $stackTraceC->getId());
        $this->assertInternalType('string', $stackTraceA->getId());
        $this->assertSame(32, \mb_strlen($stackTraceA->getId()));
    }

    /**
     * @dataProvider providerToString
     *
     * @param array  $rawStackTrace
     * @param string $expected
     */
    public function testToString(array $rawStackTrace, $expected)
    {
        $stackTrace = new StackTrace($rawStackTrace, new LightVarDumper(), null);
        $expected = \str_replace('%file%', __FILE__, $expected);
        $result = (string)$stackTrace;
        $this->assertInternalType('string', $result);
        $this->assertSame($expected, $result);
    }

    public function providerToString()
    {
        return array(
            array(
                array(
                    array('file' => __FILE__, 'line' => 15, 'function' => 'run', 'type' => '->', 'class' => 'Awesomite\MyApp'),
                    array('file' => __FILE__, 'line' => 23, 'function' => 'handleHttp', 'type' => '->', 'class' => 'Awesomite\MyApp\Http\Handler'),
                ),
                <<<OUTPUT
#0 Awesomite\MyApp->run() at %file%:15
#1 Awesomite\MyApp\Http\Handler->handleHttp() at %file%:23
OUTPUT
            ),
            array(
                array(
                    array('file' => __FILE__, 'line' => 17),
                ),
                '#0 %file%:17',
            ),
            array(
                array(
                    array('function' => 'eval'),
                ),
                '#0 eval()',
            ),
        );
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
        if (\version_compare(\PHP_VERSION, '5.6') >= 0) {
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

    /**
     * @dataProvider providerConvertArg
     *
     * @param false|int $threshold
     * @param mixed     $value
     * @param string    $expectedReturnedClass
     */
    public function testConvertArg($threshold, $value, $expectedReturnedClass)
    {
        $stackTrace = new StackTrace(array(), new LightVarDumper(), $threshold);
        $method = new \ReflectionMethod($stackTrace, 'convertArg');
        $method->setAccessible(true);
        $result = $method->invoke($stackTrace, $value);
        $this->assertInstanceOf('Awesomite\StackTrace\Arguments\Values\ValueInterface', $result);
        $this->assertInstanceOf($expectedReturnedClass, $result);
    }

    public function providerConvertArg()
    {
        return array(
            array(null, null, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(5, null, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(null, false, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(null, 5, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(null, 5.0, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(null, \INF, 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(5, '00000', 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(null, '00000', 'Awesomite\StackTrace\Arguments\Values\Value'),
            array(5, '000000', 'Awesomite\StackTrace\Arguments\Values\DeserializedValue'),
            array(0, 'a', 'Awesomite\StackTrace\Arguments\Values\DeserializedValue'),
            array(null, new \stdClass(), 'Awesomite\StackTrace\Arguments\Values\DeserializedValue'),
            array(null, array(), 'Awesomite\StackTrace\Arguments\Values\DeserializedValue'),
            array(null, \tmpfile(), 'Awesomite\StackTrace\Arguments\Values\DeserializedValue'),
        );
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
