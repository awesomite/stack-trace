<?php

namespace Awesomite\StackTrace\SourceCode;

/**
 * @internal
 */
interface FileInterface extends \Serializable
{
    /**
     * @return int
     */
    public function countLines();

    /**
     * @param int $from
     * @param int $to
     * @return string[]
     */
    public function getLines($from, $to);

    /**
     * @return string
     */
    public function getFileName();
}