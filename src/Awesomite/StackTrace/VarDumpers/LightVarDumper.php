<?php

namespace Awesomite\StackTrace\VarDumpers;

use Awesomite\StackTrace\VarDumpers\Properties\Properties;
use Awesomite\StackTrace\VarDumpers\Properties\PropertyInterface;

class LightVarDumper extends InternalVarDumper
{
    private $limit = 20;

    private $maxStringLength = 200;

    private $objects = array();

    public function dump($var)
    {
        if (is_string($var)) {
            $this->dumpString($var);
            return;
        }

        if (is_null($var)) {
            echo "NULL\n";
            return;
        }

        if (is_scalar($var)) {
            $this->dumpScalar($var);
            return;
        }

        if (is_object($var)) {
            $isFirst = empty($this->objects);
            $this->dumpObj($var);
            if ($isFirst) {
                $this->objects = array();
            }
            return;
        }

        if (is_array($var)) {
            $this->dumpArray($var);
            return;
        }

        if (is_resource($var)) {
            $this->dumpResource($var);
            return;
        }

        // @codeCoverageIgnoreStart
        // Theoretically the following line is unnecessary
        parent::dump($var);
        // @codeCoverageIgnoreStop
    }

    private function dumpResource($resource)
    {
        echo 'resource of type ', get_resource_type($resource), "\n";
    }

    private function dumpScalar($scalar)
    {
        $mapping = array(
            'boolean' => 'bool',
            'integer' => 'int',
        );
        $type = gettype($scalar);
        if (isset($mapping[$type])) {
            $type = $mapping[$type];
        }

        echo $type . '(' . var_export($scalar, true) . ")\n";
    }

    private function dumpString($string)
    {
        $len = strlen($string);
        $suffix = '';
        if ($len > $this->maxStringLength) {
            $string = substr($string, 0, $this->maxStringLength);
            $suffix = '...';
        }

        echo "string({$len}) ";
        var_export($string);
        echo $suffix . "\n";
    }

    private function dumpArray($array)
    {
        $limit = $this->limit;
        echo 'array(' . count($array) . ') {' . "\n";
        foreach ($array as $key => $value) {
            $valDump = str_replace("\n", "\n  ", $this->getDump($value));
            $valDump = substr($valDump, 0, -2);
            echo "  [{$key}] => \n  {$valDump}";
            if (!--$limit) {
                if (count($array) > $this->limit) {
                    echo "  (...)\n";
                }
                break;
            }
        }
        echo '}' . "\n";
    }

    private function dumpObj($object)
    {
        if (in_array($object, $this->objects, true)) {
            echo 'RECURSIVE object(' . get_class($object) . ")\n";
            return;
        }
        $this->objects[] = $object;

        $limit = $this->limit;
        $propertiesIterator = new Properties($object);
        /** @var PropertyInterface[] $properties */
        $properties = $propertiesIterator->getProperties();
        $class = get_class($object);
        echo 'object(' . $class . ') (' . count($properties) . ') {' . "\n";
        foreach ($properties as $property) {
            $valDump = str_replace("\n", "\n  ", $this->getDump($property->getValue()));
            $valDump = substr($valDump, 0, -2);
            $declaringClass = '';
            if ($property->getDeclaringClass() !== $class) {
                $declaringClass = " @{$property->getDeclaringClass()}";
            }
            echo "  {$this->getTextTypePrefix($property)}\${$property->getName()}{$declaringClass} => \n  {$valDump}";
            if (!--$limit) {
                if (count($properties) > $this->limit) {
                    echo "  (...)\n";
                }
                break;
            }
        }
        echo '}' . "\n";
    }

    private function getTextTypePrefix(PropertyInterface $property)
    {
        $prefix = $property->isStatic() ? 'static ' : '';

        if ($property->isPublic()) {
            return $prefix . 'public ';
        }

        if ($property->isProtected()) {
            return $prefix . 'protected ';
        }

        return $prefix . 'private ';
    }
}