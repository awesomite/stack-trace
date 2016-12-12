<?php

namespace Awesomite\StackTrace\VarDumpers;

/**
 * @internal
 */
class TestArrayObject extends \ArrayObject
{
    private $privateProperty = 'private value';
}