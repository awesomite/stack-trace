<?php

namespace Awesomite\StackTrace\VarDumpers;

class TestObject extends TestParent
{
    public $public;

    protected $protected;

    public function setProtected($value)
    {
        $this->protected = $value;
    }
}