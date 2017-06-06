<?php

namespace Awesomite\StackTrace;

/**
 * @internal
 */
class SyntaxTest extends BaseTestCase
{
    public function testSyntax()
    {
        $delimiter = DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;
        $explodedPath = explode($delimiter, __FILE__);
        array_pop($explodedPath);
        $path = realpath(implode($delimiter, $explodedPath) . DIRECTORY_SEPARATOR . 'src');
        $this->assertInternalType('string', $path);
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.php$/', \RecursiveRegexIterator::GET_MATCH);
        $counter = 0;
        $toSkip = $this->getToSkip();
        foreach ($regex as $file) {
            foreach ($toSkip as $pattern) {
                if (preg_match($pattern, $file[0])) {
                    continue 2;
                }
            }
            $counter++;
            require_once $file[0];
        }
        $this->assertGreaterThan(0, $counter);
    }

    /**
     * Returns array of patterns
     *
     * @return array
     */
    private function getToSkip()
    {
        $result = array();

        $subpath = implode(DIRECTORY_SEPARATOR, array('src', 'Exceptions', 'StackTraceException.php'));
        $result[] = '#' . preg_quote($subpath, '#') . '#';

        return $result;
    }
}
