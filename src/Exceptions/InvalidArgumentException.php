<?php

/*
 * This file is part of the awesomite/stack-trace package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\StackTrace\Exceptions;

/**
 * TODO make this class internal in v2.0.0
 */
class InvalidArgumentException extends \InvalidArgumentException implements StackTraceException
{
}
