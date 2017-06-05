#!/usr/bin/env bash

set -ev

IS_HHVM_PHP7=$(php -d "hhvm.php7.all=1" -r "echo defined('HHVM_VERSION') && version_compare(PHP_VERSION, '7.0')>=0?'1':'0';")
if [ ${IS_HHVM_PHP7} == "1" ]
then
    php -d hhvm.php7.all=1 -r "echo phpversion() . PHP_EOL;"

    composer update --prefer-lowest --no-interaction
    php -d hhvm.php7.all=1 -d error_reporting=$(php -r "var_export(E_ALL & ~E_DEPRECATED);") -d hhvm.jit=0 vendor/bin/phpunit --no-coverage

    composer update --no-interaction
    php -d hhvm.php7.all=1 -d hhvm.jit=0 vendor/bin/phpunit --no-coverage
fi
