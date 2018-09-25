<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Awesomite\StackTrace\Arguments\ArgumentInterface;
use Awesomite\StackTrace\Arguments\ArgumentsInterface;
use Awesomite\StackTrace\StackTraceFactory;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
class StackTracePrinter
{
    public function printStackTrace()
    {
        $factory = new StackTraceFactory();
        $stackTrace = $factory->create();
        $i = 0;
        foreach ($stackTrace as $step) {
            $this->printStep($step, ++$i);
        }
    }

    private function printStep(StepInterface $step, $i)
    {
        $function = $step->hasCalledFunction() ? $step->getCalledFunction()->getName() : 'unknown';

        echo "#{$i} {$function}(";
        $arguments = $step->getArguments();
        $first = true;
        foreach ($arguments as $argument) {
            if ($argument->hasDeclaration()) {
                if ($first) {
                    $first = false;
                } else {
                    echo ', ';
                }

                echo '$' . $argument->getDeclaration()->getName();
            } else {
                break;
            }
        }
        echo ")\n";

        if ($step->hasPlaceInCode()) {
            echo "  Place in code:\n";
            $placeInCode = $step->getPlaceInCode();
            $fileName = $this->shortFileName($placeInCode->getFileName());
            echo "    {$fileName}:{$placeInCode->getLineNumber()}\n";
        }

        $this->printArguments($step->getArguments());
    }

    /**
     * @param ArgumentInterface[]|ArgumentsInterface $arguments
     */
    private function printArguments($arguments)
    {
        if (!\count($arguments)) {
            return;
        }

        echo "  Arguments:\n";

        foreach ($arguments as $argument) {
            if ($argument->hasValue()) {
                $dump = $argument->getValue();
            } else {
                $dump = 'undefined';
                if ($argument->hasDeclaration()) {
                    $declaration = $argument->getDeclaration();
                    if ($declaration->hasDefaultValue()) {
                        $dumper = new LightVarDumper();
                        $defDump = $dumper->dumpAsString($declaration->getDefaultValue());
                        $dump .= ' (default ' . \substr($defDump, 0, -1) . ')';
                    }
                }
                $dump .= "\n";
            }

            $tabs = '    ';

            $dump = \substr($dump, 0, -1);
            $dump = $tabs . \str_replace("\n", "\n", $dump) . "\n";
            echo $dump;
        }
    }

    private function shortFileName($file)
    {
        $exploded = \explode(\DIRECTORY_SEPARATOR, $file);
        $shifted = false;
        while (\count($exploded) > 3) {
            \array_shift($exploded);
            $shifted = true;
        }

        if ($shifted) {
            \array_unshift($exploded, '(...)');
        }

        return \implode(\DIRECTORY_SEPARATOR, $exploded);
    }
}
