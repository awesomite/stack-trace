<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Arguments\Values;

use Awesomite\VarDumper\InternalVarDumper;
use Awesomite\VarDumper\VarDumperInterface;

/**
 * @internal
 */
class Value implements ValueInterface, \Serializable
{
    private $value;

    private $dumpedVar = null;

    /**
     * @var VarDumperInterface
     */
    private $varDumper;

    /**
     * @codeCoverageIgnore
     *
     * @param mixed              $value
     * @param VarDumperInterface $varDumper
     */
    public function __construct($value, VarDumperInterface $varDumper = null)
    {
        $this->value = $value;
        $this->varDumper = $varDumper ?: new InternalVarDumper();
    }

    public function getRealValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->dumpAsString();
    }

    public function dump()
    {
        if (!\is_null($this->dumpedVar)) {
            echo $this->dumpedVar;

            return;
        }

        $this->varDumper->dump($this->getRealValue());
    }

    public function dumpAsString()
    {
        \ob_start();
        $this->dump();
        $result = \ob_get_contents();
        \ob_end_clean();

        return $result;
    }

    public function getDump()
    {
        return $this->dumpAsString();
    }

    public function isRealValueReadable()
    {
        return true;
    }

    public function serialize()
    {
        return \serialize(array(
            'value'     => $this->value,
            'dumpedVar' => !\is_null($this->dumpedVar)
                ? $this->dumpedVar
                : $this->varDumper->dumpAsString($this->value),
        ));
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        $this->value = $data['value'];
        $this->dumpedVar = $data['dumpedVar'];
    }
}
