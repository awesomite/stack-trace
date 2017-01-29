<?php

namespace Awesomite\StackTrace\Steps;

use Awesomite\StackTrace\Arguments\Arguments;
use Awesomite\StackTrace\Constants;
use Awesomite\StackTrace\Functions\AFunction;
use Awesomite\StackTrace\SourceCode\PlaceInCode;
use Awesomite\StackTrace\Exceptions\LogicException;

/**
 * @internal
 */
class Step implements StepInterface
{
    private $stepArray;

    private $function = null;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(array $stepArray)
    {
        $this->stepArray = $stepArray;
    }

    public function getArguments()
    {
        return new Arguments(
            $this->getArgsFromArray(),
            $this->hasCalledFunction() ? $this->getCalledFunction() : null
        );
    }

    public function getPlaceInCode()
    {
        if (!$this->hasPlaceInCode()) {
            throw new LogicException('There is no source code for this step!');
        }

        return new PlaceInCode(
            $this->stepArray['file'],
            $this->stepArray['line'],
            isset($this->stepArray[Constants::KEY_FILE_OBJECT])
                ? $this->stepArray[Constants::KEY_FILE_OBJECT]
                : null
        );
    }

    public function hasPlaceInCode()
    {
        return isset($this->stepArray[Constants::KEY_FILE_OBJECT]) ||
            (isset($this->stepArray['file']) && is_file($this->stepArray['file']) && !empty($this->stepArray['line']));
    }

    public function hasCalledFunction()
    {
        return isset($this->stepArray['function']);
    }

    public function getCalledFunction()
    {
        if (!$this->hasCalledFunction()) {
            throw new LogicException('There is no called function for this step!');
        }

        if (is_null($this->function)) {
            $this->function = new AFunction($this->stepArray);
        }

        return $this->function;
    }

    private function getArgsFromArray()
    {
        return isset($this->stepArray['args']) ? $this->stepArray['args'] : array();
    }
}