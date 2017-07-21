# Changelog

## 0.4.3

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.4.2

* Fixed bug "Undefined property: ClassName::$propertyName" - property can be defined in code, but removed in runtime

## 0.4.1

* Fixed bug: `Call to a member function getDump() on null`

## 0.4.0

* Fixed bug: `Undefined property: ArrayObject::$propertyName`

## 0.3.2

* Corrected value of `StackTrace::VERSION`

## 0.3.1

* Fixed "Nesting level too deep - recursive dependency?" in old versions of PHP

## 0.3.0

* `LightVarDumper` can handle all types of variables
* `LightVarDumper` became default dumper
* Added methods:
  * `LightVarDumper::setMaxDepth()`
  * `LightVarDumper::setMaxStringLength()`
  * `LightVarDumper::setMaxChildren()`
* Refactored namespace `Awesomite\StackTrace\VarDumpers\Properties`

## 0.2.0

* Added support for objects in `LightVarDumper`

## 0.1.1

* Store in serialized variable also contents of files

## 0.1.0

* Initial public release