<?php

namespace Awesomite\StackTrace\VarDumpers\Properties;

/**
 * @internal
 */
interface PropertyInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isStatic();

    /**
     * @return bool
     */
    public function isPublic();

    /**
     * @return bool
     */
    public function isProtected();

    /**
     * @return bool
     */
    public function isPrivate();

    public function getValue();

    /**
     * @return string
     */
    public function getDeclaringClass();
}