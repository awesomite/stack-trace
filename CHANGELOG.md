# Changelog

## 0.10.2 (2017-08-30)

* Updated `awesomite/var-dumper` to version `^0.6.3 || ^0.7.2 || ^0.8.0`

## 0.10.1 (2017-06-06)

* Removed support for PHPUnit ^5.0 and ^6.0 - there are issue with HHVM and `Throwable`,
in HHVM `Exception` does not implement `Throwable`

## 0.10.0 (2017-06-06)

* Removed `Awesomite\StackTrace\Arguments\Values\MultipleValues`
* Added tests for HHVM with flag `hhvm.php7.all=1`
* `Awesomite\StackTrace\StackTrace::unserialize` throws `Awesomite\StackTrace\Exceptions\LogicException` instead of `LogicException`

## 0.9.2 (2017-06-04)

* Fixed phpdoc for `Awesomite\StackTrace\Arguments\ArgumentsInterface::getIterator`
* Fixed [bug](https://travis-ci.org/awesomite/stack-trace/jobs/239395658) in HHVM

## 0.9.1 (2017-05-05)

* Removed bin/phpunit.sh, use vendor/bin/phpunit instead
* Added `.gitattributes` file

## 0.9.0 (2017-05-04)

* Documentation has been moved to docs directory
* Removed file `bin/local-tests.sh` - tests can be executed in docker, it is easier way
* Changed coding style to PSR-2
* Support for PHPUnit ^5.7 and ^6.1

## 0.8.1 (2017-04-11)

* Fixed bug - last character of last line fetched by `Awesomite\StackTrace\SourceCode\File` was not displayed when file was not ended by empty line

## 0.8.0 (2017-03-14)

* Deprecated classes `Awesomite\StackTrace\VarDumpers\*` have been removed

## 0.7.3 (2017-03-14)

* Links in documentation have been fixed

## 0.7.2 (2017-02-05)

* Value of `StackTrace::VERSION` has been corrected

## 0.7.1 (2017-02-02)

* Version of `awesomite/var-dumper` has been updated to `^0.3.0`

## 0.7.0 (2017-01-31)

* Decreased size of serialized stack traced;
* `Awesomite\StackTrace\StackTrace::getIterator` returns `Awesomite\Iterators\CallbackIterator`

## 0.6.1 (2017-01-18)

* Updated `awesomite/var-dumper` to version `^0.2.0`

## 0.6.0 (2017-01-16)

* Moved `LightVarDumper` to separate repository (`awesomite/var-dumper`)

## 0.5.4 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.5.3 (2017-06-18)

* Fixed bug: `Undefined property: ClassName::$propertyName` - property can be defined in code, but removed in runtime

## 0.5.2 (2017-01-11)

* Value of `StackTrace::VERSION` has been corrected

## 0.5.1 (2017-01-10)

* Check if file exists when contents is not initialized yet - `\Awesomite\StackTrace\SourceCode\PlaceInCode`

## 0.5.0 (2017-01-10)

* Decreased size of serialized stack trace - contents of file should be shared between steps of stack trace
* Improved handling of `\ArrayObject` in `\Awesomite\StackTrace\VarDumpers\LightVarDumper`
* Fixed issue related to HHVM and `\ArrayObject` in `\Awesomite\StackTrace\VarDumpers\LightVarDumper` -
HHVM can see properties `storage`, `flags` and `iteratorClass` of `\ArrayObject`,
fix in class `\Awesomite\StackTrace\VarDumpers\Properties\PropertiesArrayObject`
* Fixed checking if property is virtual in HHVM - `\Awesomite\StackTrace\VarDumpers\Properties\ReflectionProperty::isVirtual`

## 0.4.3 (2017-07-21)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.4.2 (2017-06-18)

* Fixed bug: `Undefined property: ClassName::$propertyName` - property can be defined in code, but removed in runtime

## 0.4.1 (2017-01-04)

* Fixed bug: `Call to a member function getDump() on null`

## 0.4.0 (2016-12-12)

* Fixed bug: `Undefined property: ArrayObject::$propertyName`

## 0.3.4 (2017-07-21)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.3.3 (2017-06-18)

* Fixed bug: `Undefined property: ClassName::$propertyName` - property can be defined in code, but removed in runtime

## 0.3.2 (2016-12-04)

* Corrected value of `StackTrace::VERSION`

## 0.3.1 (2016-12-04)

* Fixed "Nesting level too deep - recursive dependency?" in old versions of PHP

## 0.3.0 (2016-12-04)

* `LightVarDumper` can handle all types of variables
* `LightVarDumper` became default dumper
* Added methods:
  * `LightVarDumper::setMaxDepth()`
  * `LightVarDumper::setMaxStringLength()`
  * `LightVarDumper::setMaxChildren()`
* Refactored namespace `Awesomite\StackTrace\VarDumpers\Properties`

## 0.2.3 (2017-07-21)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.2.2 (2017-06-18)

* Value of `StackTrace::VERSION` has been corrected

## 0.2.1 (2017-06-18)

* Fixed bug: `Undefined property: ClassName::$propertyName` - property can be defined in code, but removed in runtime

## 0.2.0 (2016-12-01)

* Added support for objects in `LightVarDumper`

## 0.1.2 (2016-11-30)

* Fixed bug: `Awesomite\ErrorDumper\StandardExceptions\FatalErrorException E_WARNING file_get_contents(newrelic/Guzzle6): failed to open stream: No such file or directory`

## 0.1.1 (2016-11-29)

* Store in serialized variable also contents of files

## 0.1.0 (2016-11-23)

* Initial public release
