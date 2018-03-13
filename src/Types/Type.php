<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Types;

/**
 * @internal
 */
class Type implements TypeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        if (\defined('HHVM_VERSION')) {
            foreach (array('HH\\', '?HH\\') as $prefix) {
                if (0 === \strpos($name, $prefix)) {
                    $name = \substr($name, \strlen($prefix));
                    break;
                }
            }
        }
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
