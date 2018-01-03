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
class UnsafeFunctionsTest extends BaseTestCase
{
    private static $unsafeFunctions
        = array(
            'system',
            'exec',
            'popen',
            'pcntl_exec',
            'eval',
            'create_function',
            'preg_replace', // /e
            'override_function',
            'rename_function',
            'var_dump',
            'print_r',
            'mb_ereg_replace', // /e
            'mb_eregi_replace', // /e
        );

    /**
     * @dataProvider providerFiles
     */
    public function testPhp($filePath)
    {
        foreach (\token_get_all(\file_get_contents($filePath)) as $tokenArr) {
            if (!\is_array($tokenArr)) {
                if ('`' === $tokenArr) {
                    $this->fail("Backtick operator is forbidden {$filePath}");
                }
                continue;
            }
            list($token, $source, $line) = $tokenArr;
            $source = \mb_strtolower($source);

            switch ($token) {
                case T_STRING:
                    if (\in_array($source, self::$unsafeFunctions, true)) {
                        $this->fail("Function {$source} in {$filePath}:{$line}");
                    }
                    break;

                case T_EXIT:
                case T_EVAL:
                    $this->fail("Function {$source} in {$filePath}:{$line}");
                    break;
            }
        }
    }

    public function providerFiles()
    {
        $exploded = \explode(DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR, __DIR__);
        \array_pop($exploded);
        $exploded[] = 'src';
        $path = \implode(DIRECTORY_SEPARATOR, $exploded);
        $pattern = '/^.+\.php$/';

        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);

        return new \RegexIterator($iterator, $pattern, \RecursiveRegexIterator::GET_MATCH);
    }
}
