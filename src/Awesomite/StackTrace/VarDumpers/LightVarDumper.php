<?php

namespace Awesomite\StackTrace\VarDumpers;

class LightVarDumper extends InternalVarDumper
{
    private $limit = 20;

    public function dump($var)
    {
        if (is_scalar($var) || is_resource($var)) {
            parent::dump($var);
            return;
        }

        if (is_object($var)) {
            echo 'object(' . get_class($var) . ") {}\n";
            return;
        }

        if (is_array($var)) {
            $this->dumpArray($var);
            return;
        }

        parent::dump($var);
    }

    private function dumpArray($array)
    {
        $limit = $this->limit;
        echo 'array(' . count($array) . ') {' . "\n";
        foreach ($array as $key => $value) {
            $valDump = str_replace("\n", "\n  ", $this->getDump($value));
            $valDump = substr($valDump, 0, -2);
            echo "  [{$key}] => \n  {$valDump}";
            if (!--$limit) {
                if (count($array) > $this->limit) {
                    echo "  (...)\n";
                }
                break;
            }
        }
        echo '}' . "\n";
    }
}