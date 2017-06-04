<?php

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
        $steps = iterator_to_array($stackTrace->getIterator());
        $step = $steps[1];
        /** @var ArgumentInterface[] $args */
        $args = iterator_to_array($step->getArguments());
        $this->testCase->assertSame(3, count($args));
        $this->testCase->assertSame(empty($third), !$args[2]->hasValue());
    }
}
