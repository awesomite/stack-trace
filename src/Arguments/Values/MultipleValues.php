<?php

namespace Awesomite\StackTrace\Arguments\Values;

/**
 * @internal
 */
class MultipleValues implements ValueInterface
{
    private $values;

    private $limit;

    /**
     * MultipleValues constructor.
     * @param ValueInterface[] $values
     * @param int $limit
     */
    public function __construct(array $values, $limit = 20)
    {
        $this->values = $values;
        $this->limit = $limit;
    }

    public function isRealValueReadable()
    {
        return false;
    }

    public function getRealValue()
    {
        throw new CannotRestoreValueException('Cannot restore value!');
    }

    public function __toString()
    {
        return $this->getDump();
    }

    public function dump()
    {
        $limit = $this->limit;
        echo 'array(' . count($this->values) . ') {' . "\n";
        foreach ($this->values as $key => $value) {
            $valDump = str_replace("\n", "\n  ", $value->getDump());
            $valDump = substr($valDump, 0, -2);
            echo "  [{$key}] => \n  {$valDump}";
            if (!--$limit) {
                if (count($this->values) > $this->limit) {
                    echo "  (...)\n";
                }
                break;
            }
        }
        echo '}' . "\n";
    }

    public function getDump()
    {
        ob_start();
        $this->dump();
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}
