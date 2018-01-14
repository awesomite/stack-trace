# StackTrace

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a8e897fed2874e408854c34da0493019)](https://www.codacy.com/app/awesomite/stack-trace?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/stack-trace&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/stack-trace/badge.svg?branch=master)](https://coveralls.io/github/awesomite/stack-trace?branch=master)
[![Build Status](https://travis-ci.org/awesomite/stack-trace.svg?branch=master)](https://travis-ci.org/awesomite/stack-trace)

Abstract layer for [`debug_backtrace()`](http://php.net/manual/en/function.debug-backtrace.php) function.
This library allows you to serialize whole stack trace including variables.
It handles all types of data, including resources.

## Usage

See [documentation](docs/DOCUMENTATION.md).

```php
<?php

use Awesomite\StackTrace\StackTraceFactory;
use Awesomite\StackTrace\Steps\StepInterface;
use Awesomite\StackTrace\SourceCode\PlaceInCodeInterface;

$factory = new StackTraceFactory();
$stackTrace = $factory->create();
foreach ($stackTrace as $step) {
    /** @var StepInterface $step */
    
    $function = $step->getCalledFunction()->getName();
    echo "Function {$function}";
    
    if ($step->hasPlaceInCode()) {
        /** @var PlaceInCodeInterface $placeInCode */
        $placeInCode = $step->getPlaceInCode();
        $fileName = $placeInCode->getFileName();
        $line = $placeInCode->getLineNumber();
        $function = $step->getCalledFunction()->getName();
        echo " is called from {$fileName}:{$line}";
    }
    
    echo "\n";
}

$data = serialize($stackTrace);
$unserializedStackTrace = unserialize($data);
```

## Installation

`composer require awesomite/stack-trace`

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.
[Read more](docs/DOCUMENTATION.md#backward-compatibility) about backward compatibility.

## Examples

[See](examples) more examples.

## License

This library is released under the [MIT license](LICENSE).
