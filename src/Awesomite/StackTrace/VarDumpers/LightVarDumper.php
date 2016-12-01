<?php

namespace Awesomite\StackTrace\VarDumpers;

use Awesomite\StackTrace\VarDumpers\Properties\Properties;
use Awesomite\StackTrace\VarDumpers\Properties\PropertyInterface;

class LightVarDumper extends InternalVarDumper
{
    private $limit = 20;

    public function dump($var)
    {
        if (is_scalar($var) || is_resource($var)) {
            parent::dump($var);
            return;
        }

        if (is_object($var)) {
            $this->dumpObj($var);
            return;
        }

        if (is_array($var)) {
            $this->dumpArray($var);
            return;
        }

        parent::dump($var);
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