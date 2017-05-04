<?php

if (version_compare(PHP_VERSION, '7.2') >= 0) {
    error_reporting(E_ALL & ~E_DEPRECATED);
}

require implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'vendor', 'autoload.php'));
