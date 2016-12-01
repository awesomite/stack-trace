<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

/**
 * @internal
 */
interface PropertiesInterface
{
    /**
     * @return PropertyInterface[]|\Traversable
     */
    public function getProperties();
}