# StackTrace

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a8e897fed2874e408854c34da0493019)](https://www.codacy.com/app/awesomite/stack-trace?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/stack-trace&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/stack-trace/badge.svg?branch=master)](https://coveralls.io/github/awesomite/stack-trace?branch=master)
[![Build Status](https://travis-ci.org/awesomite/stack-trace.svg?branch=master)](https://travis-ci.org/awesomite/stack-trace)

Abstract layer for [`debug_backtrace()`](http://php.net/manual/en/function.debug-backtrace.php) function.

## Usage

See [documentation](docs/DOCUMENTATION.md).

```php
use Awesomite\StackTrace\StackTraceFactory;

$factory = new StackTraceFactory();
$stackTrace = $factory->create();
foreach ($stackTrace as $step) {
    /** @var StepInterface $step */
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
[Read more](docs/DOCUMENTATION.md#backward-compatibility) about backward compatibility.
