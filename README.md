# StackTrace

Abstract layer for [`debug_backtrace()`](http://php.net/manual/en/function.debug-backtrace.php) function.

## Usage

See [documentation](DOCUMENTATION.md).

```php
use Awesomite\StackTrace\StackTraceFactory;

$factory = new StackTraceFactory();
$stackTrace = $factory->create();
foreach ($stackTrace as $step) {
    $placeInCode = $step->getPlaceInCode();
    $line = $placeInCode->getLineNumber();
    $fileName = $placeInCode->getFileName();
    $function = $step->getCalledFunction()->getName();
    echo "Function {$function} is called from {$fileName}:{$line}\n";
}
```

## Installation

`composer require awesomite/stack-trace`

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.
[Read more](DOCUMENTATION.md#backward-compatibility) about backward compatibility.

## Running tests

See [documentation](TESTS.md) for tests.