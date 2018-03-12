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

/**
 * @internal
 */
class SyntaxTest extends BaseTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSyntax()
    {
        $delimiter = DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;
        $explodedPath = \explode($delimiter, __FILE__);
        \array_pop($explodedPath);
        $path = \realpath(\implode($delimiter, $explodedPath) . DIRECTORY_SEPARATOR . 'src');
        $this->assertInternalType('string', $path);
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.php$/', \RecursiveRegexIterator::GET_MATCH);
        $counter = 0;
        foreach ($regex as $file) {
            $counter++;
            require_once $file[0];
        }
        $this->assertGreaterThan(0, $counter);
    }
}
