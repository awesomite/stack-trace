<?php

if (interface_exists('PHPUnit_Framework_TestListener')) {
    class_alias(
        'Awesomite\StackTrace\Listeners\BridgeTestListener4x',
        'Awesomite\StackTrace\Listeners\BridgeTestListener'
    );
} else {
    class_alias(
        'Awesomite\StackTrace\Listeners\BridgeTestListener6x',
        'Awesomite\StackTrace\Listeners\BridgeTestListener'
    );
}
