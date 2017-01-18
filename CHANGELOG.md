# Changelog

## 0.6.1

* Updated `awesomite/var-dumper` to version `^0.2.0`

## 0.6.0

* Moved `LightVarDumper` to separate repository (`awesomite/var-dumper`)

## 0.5.1

* Check if file exists when contents is not initialized yet - `\Awesomite\StackTrace\SourceCode\PlaceInCode`

## 0.5.0

* Decreased size of serialized stack trace - contents of file should be shared between steps of stack trace
* Improved handling of `\ArrayObject` in `\Awesomite\StackTrace\VarDumpers\LightVarDumper`
* Fixed issue related to HHVM and `\ArrayObject` in `\Awesomite\StackTrace\VarDumpers\LightVarDumper` -
HHVM can see properties `storage`, `flags` and `iteratorClass` of `\ArrayObject`,
fix in class `\Awesomite\StackTrace\VarDumpers\Properties\PropertiesArrayObject`
* Fixed checking if property is virtual in HHVM - `\Awesomite\StackTrace\VarDumpers\Properties\ReflectionProperty::isVirtual`

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