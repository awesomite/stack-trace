# StackTrace documentation

## Create stack trace object

To create stack trace use `StackTraceFactory->create()` or `StackTraceFactory->createByThrowable()`.
Both methods have optional `$limit` parameter.

```php
use Awesomite\StackTrace\StackTraceFactory;

$factory = new StackTraceFactory();

// creates stack trace for current position
$currentStackTrace = $factory->create();

set_exception_handler(function ($exception) {
    /** @var \Exception|\Throwable $exception */
    // creates stack trace for \Exception or \Throwable
    $exceptionStackTrace = $factory->createByThrowable($exception);
});
```

## Backward compatibility

**Do not use `@internal` classes, methods and properties.**

Almost all classes in project are `@internal`.
It means you should not use directly those classes in your project,
because backward compatibility is not supported for them and everything can change,
including constructor, class name and constants.

### Factories

This project uses factories instead of public classes.
Factory gives you object which implements proper interface, so you always have enough knowledge about object.

## Iterate on stack trace

[`StackTraceInterface`](src/Awesomite/StackTrace/StackTraceInterface.php) implements [`Traversable`](http://php.net/manual/en/class.traversable.php) interface, it means `$stackTrace` is iterable. 

```php
foreach ($stackTrace as $step) {
    // do something
}
```

## Reading values

Before `$step->getPlaceInCode();` you should check if place in code is available, method `getPlaceInCode()` will help you.
Otherwise method `getPlaceInCode()` can throw exception.
This principle is applied in almost all cases, except cases when returned value is always available.

## List of interfaces

* `$stackTrace = $factory->create();` [Awesomite\StackTrace\StackTraceInterface](src/Awesomite/StackTrace/StackTraceInterface.php)
* `foreach ($stackTrace as $step) {}` [Awesomite\StackTrace\Steps\StepInterface](src/Awesomite/StackTrace/Steps/StepInterface.php)
* `$placeInCode = $step->getPlaceInCode();` [Awesomite\StackTrace\SourceCode\PlaceInCodeInterface](src/Awesomite/StackTrace/SourceCode/PlaceInCodeInterface.php)
* `$lines = $placeInCode->getAdjacentCode();` [Awesomite\StackTrace\SourceCode\Lines\LinesInterface](src/Awesomite/StackTrace/SourceCode/Lines/LinesInterface.php)
* `foreach ($lines as $line) {}` [Awesomite\StackTrace\SourceCode\Lines\LineInterface](src/Awesomite/StackTrace/SourceCode/Lines/LineInterface.php)
* `$function = $step->getCalledFunction();` [Awesomite\StackTrace\Functions\FunctionInterface](src/Awesomite/StackTrace/Functions/FunctionInterface.php)
* `$arguments = $step->getArguments();` [Awesomite\StackTrace\Arguments\ArgumentsInterface](src/Awesomite/StackTrace/Arguments/ArgumentsInterface.php)
* `foreach ($arguments as $argument) {}` [Awesomite\StackTrace\Arguments\ArgumentInterface](src/Awesomite/StackTrace/Arguments/ArgumentInterface.php)
* `$declaration = $argument->getDeclaration();` [Awesomite\StackTrace\Arguments\Declarations\DeclarationInterface](src/Awesomite/StackTrace/Arguments/Declarations/DeclarationInterface.php)
* `$type = $declaration->getType();` [Awesomite\StackTrace\Types\TypeInterface](src/Awesomite/StackTrace/Types/TypeInterface.php)
* `$value = $argument->getValue();` [Awesomite\StackTrace\Arguments\Values\ValueInterface](src/Awesomite/StackTrace/Arguments/Values/ValueInterface.php)