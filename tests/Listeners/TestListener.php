<?php

namespace Awesomite\StackTrace\Listeners;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @internal
 */
class TestListener extends BridgeTestListener
{
    private $offset = .05;

    private static $messages = array();

    public static function logMessage($message)
    {
        self::$messages[] = $message;
    }

    public function __destruct()
    {
        $output = $this->getConsoleOutput();
        foreach (self::$messages as $message) {
            $output->writeln($message);
        }
    }

    protected function _endTest($test, $time)
    {
        parent::_endTest($test, $time);

        if ($time < $this->offset) {
            return;
        }

        $name = ($test instanceof \PHPUnit_Framework_TestCase) || ($test instanceof TestCase)
            ? get_class($test) . '::' . $test->getName()
            : get_class($test);

        self::$messages[] = sprintf("<warning>Test '%s' took %0.2f seconds.</warning>",
            $name,
            $time
        );
    }

    private function getConsoleOutput()
    {
        $style = new OutputFormatterStyle();
        $style->setBackground('yellow');
        $style->setForeground('black');

        $output = new ConsoleOutput();
        $output->getFormatter()->setStyle('warning', $style);

        return $output;
    }
}
