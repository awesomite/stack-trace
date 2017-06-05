#!/usr/bin/env bash

set -ev

IS_HHVM_PHP7=$(php -d "hhvm.php7.all=1" -r "echo defined('HHVM_VERSION') && version_compare(PHP_VERSION, '7.0')>=0?'1':'0';")
if [ ${IS_HHVM_PHP7} == "1" ]
then
    echo 'hhvm.php7.all=1' >> /etc/hhvm/php.ini
    php -r "echo phpversion() . PHP_EOL;"

    sed -i '' -e '$ d' /etc/hhvm/php.ini
    composer update --prefer-lowest --no-interaction
    echo 'hhvm.php7.all=1' >> /etc/hhvm/php.ini
    php -d error_reporting=$(php -r "var_export(E_ALL & ~E_DEPRECATED);") vendor/bin/phpunit --no-coverage

    sed -i '' -e '$ d' /etc/hhvm/php.ini
    composer update --no-interaction
    echo 'hhvm.php7.all=1' >> /etc/hhvm/php.ini
    vendor/bin/phpunit --no-coverage
fi
