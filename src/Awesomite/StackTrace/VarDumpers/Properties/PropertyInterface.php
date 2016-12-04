<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

/**
 * @internal
 */
interface PropertyInterface
{
    public function getValue();

    /**
     * @return \ReflectionProperty
     */
    public function getReflection();
}