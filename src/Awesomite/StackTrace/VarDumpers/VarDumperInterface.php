<?php

namespace Awesomite\StackTrace\VarDumpers;

interface VarDumperInterface
{
    public function dump($var);

    /**
     * @param mixed $var
     * @return string
     */
    public function getDump($var);
}