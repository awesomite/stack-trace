<?php

if (version_compare(PHP_VERSION, '7.2') >= 0) {
    set_error_handler(
        function ($no, $str, $file = null, $line = null, $context = null) {
            $message = $str;
            if (!is_null($file)) {
                $message .= ' ' . $file;
            }
            if (!is_null($line)) {
                $message .= ':' . $line;
            }
            \Awesomite\StackTrace\Listeners\TestListener::logMessage("<error>${message}</error>");
        },
        E_DEPRECATED
    );
}

require implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'vendor', 'autoload.php'));
