<?php

namespace Awesomite\StackTrace\SourceCode\Lines;

interface LineInterface
{
    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return int
     */
    public function getLineNumber();

    public function __toString();
}
