# Changelog

## [1.2.0] - 2018-09-21

* Added interface [`Awesomite\StackTrace\StackTraceFactoryInterface`](./src/StackTraceFactoryInterface.php)
* Implemented interfece [`Awesomite\StackTrace\StackTraceFactoryInterface`](./src/StackTraceFactoryInterface.php)
in class [`Awesomite\StackTrace\StackTraceFactory`](./src/StackTraceFactory.php)
* Methods `getVarDumper` and `setVarDumper` in [`Awesomite\StackTrace\StackTraceInterface`](./src/StackTraceInterface.php)
have become `@deprecated`
* Added optional parameter `$varDumper` to [`Awesomite\StackTrace\StackTraceFactory::__construct`](./src/StackTraceFactory.php)
* Tests classes have become `final` whenever it was possible
* `@internal` classes have become `final` whenever it was possible
* Class `Awesomite\StackTrace\Arguments\Values\CannotRestoreValueException` has become `@deprecated`

## [1.1.0] - 2018-09-19

Added method `Awesomite\StackTrace\StackTraceInterface::getVarDumper`.

## [1.0.2] - 2018-09-15

Use `varDumper->dumpAsString()` instead of `ob_*` functions and `varDumper->dump()`
in [`Value::dumpAsString()`](./src/Arguments/Values/Value.php).

## [1.0.1] - 2018-03-18

* Added support for [eval](http://php.net/manual/en/function.eval.php)
* Fixed `Awesomite\StackTrace\Functions\AFunction::isClosure` for `HHVM`
* Fixed `Awesomite\StackTrace\Functions\AFunction::isDeprecated`:
  * returns `true` whenever class is deprecated
  * returns `false` whenever tag is invalid

## [1.0.0] - 2018-01-14

This version contains the same source code as [0.12.0].

[1.2.0]: https://github.com/awesomite/stack-trace/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/awesomite/stack-trace/compare/v1.0.2...v1.1.0
[1.0.2]: https://github.com/awesomite/stack-trace/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/awesomite/stack-trace/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/awesomite/stack-trace/tree/v1.0.0
[0.12.0]: https://github.com/awesomite/stack-trace/tree/v0.12.0
