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

    /**
     * @return bool
     */
    public function hasReflection();

    /**
     * @return string
     */
    public function getName();
}