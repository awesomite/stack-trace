#!/usr/bin/env bash

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd ${DIR}/..

if [ $(php -r "echo PHP_MAJOR_VERSION, '.', PHP_MINOR_VERSION;") == "7.2" ]
then
    ERROR_REPORTING=$(php -r "var_export(E_ALL & ~E_DEPRECATED);");
    EXTRA_PARAMS="-d error_reporting=${ERROR_REPORTING}"
else
    EXTRA_PARAMS=''
fi

php ${EXTRA_PARAMS} ${DIR}/../vendor/bin/phpunit ${@}
