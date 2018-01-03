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

/**
 * @internal
 */
class StackTraceVariadic
{
    private $testCase;

    public function __construct(BaseTestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function handleTest()
    {
        $this->testVariadic(1, 2);
        $this->testVariadic(1, 2, 3);
        $this->testVariadic(1, 2, 3, 4);
        $this->testVariadic(1, 2, 3, 4, 5);
    }

    private function testVariadic($first, $second, ...$third)
    {
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create(2);
        /** @var StepInterface[] $steps */
        $steps = \iterator_to_array($stackTrace->getIterator());
        $step = $steps[1];
        /** @var ArgumentInterface[] $args */
        $args = \iterator_to_array($step->getArguments());
        $this->testCase->assertSame(3, \count($args));
        /**
         * @see https://travis-ci.org/awesomite/stack-trace/builds/239418547
         * Bug in older HHVM versions:
         * Variadic parameter is missing in debug_backtrace()[$x]['args']
         */
        if (!\defined('HHVM_VERSION') || \version_compare(HHVM_VERSION, '3.9') >= 0) {
            $this->testCase->assertSame(empty($third), !$args[2]->hasValue());
            $this->testCase->assertTrue($args[2]->hasDeclaration());
        }

        for ($i = 0; $i < 2; $i++) {
            $this->testCase->assertTrue($args[$i]->hasValue());
            $this->testCase->assertTrue($args[$i]->hasDeclaration());
        }
    }
}
