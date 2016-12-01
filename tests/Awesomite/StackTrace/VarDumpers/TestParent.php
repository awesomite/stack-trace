<?php

namespace Awesomite\StackTrace\VarDumpers;

class TestParent
{
    private $private;

    public function setPrivate($value)
    {
        $this->private = $value;
    }
}