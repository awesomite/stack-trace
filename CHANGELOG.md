# Changelog

## [1.0.1] - ????-??-??

* Added support for [eval](http://php.net/manual/en/function.eval.php)
* Fixed `Awesomite\StackTrace\Functions\AFunction::isClosure` for `HHVM`
* Fixed `Awesomite\StackTrace\Functions\AFunction::isDeprecated`:
  * returns `true` whenever class is deprecated
  * returns `false` whenever tag is invalid

## [1.0.0] - 2018-01-14

This version contains the same source code as [0.12.0].

[1.0.0]: https://github.com/awesomite/stack-trace/tree/v1.0.0
[0.12.0]: https://github.com/awesomite/stack-trace/tree/v0.12.0
