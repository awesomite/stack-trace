#!/usr/bin/env bash

set -e

BIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR=$(dirname ${BIN_DIR})

cd ${PROJECT_DIR}

function resetTextStyle()
{
    tput sgr0
}

function printHeader()
{
    tput setaf 0
    tput setab 2
    echo -n " # $1 "
    resetTextStyle
    echo ''
}

function printInfo()
{
    echo "";
    tput setaf 0
    tput setab 7
    echo -n " ### $1 "
    resetTextStyle
}

function runTestsFor()
{
    local version=$1
    local versionDir="$PROJECT_DIR/build/php-versions/$version"
    local defaultVersion=$(php -r "echo PHP_MAJOR_VERSION, PHP_MINOR_VERSION;")
    local phpCommand="php$version"
    if [ "$version" -eq "$defaultVersion" ]; then
        phpCommand='php'
    fi

    printHeader "PHP $version"

    mkdir -p ${versionDir}
    if [ ! -f "$versionDir/autoload.php" ]; then
        cp composer.json ~composer.json

        cd ${versionDir}
        echo 'Installing composer.phar...'
        ${phpCommand} ${BIN_DIR}/install-composer.php
        cd ${PROJECT_DIR}

        echo 'Updating dependencies...'
        ${versionDir}/composer.phar config vendor-dir "build/php-versions/$version"
        ${phpCommand} "$versionDir/composer.phar" update --dev

        rm ${PROJECT_DIR}/composer.lock
        rm composer.json
        mv ~composer.json composer.json
    fi

    echo 'Executing tests...'
    ${phpCommand} "build/php-versions/$version/bin/phpunit" \
        --bootstrap "build/php-versions/$version/autoload.php" \
        --coverage-html "build/logs/$version"
    echo ""
}

function runTestsForAll
{
    for version in 53 54 55 56 70 71
    do
        runTestsFor ${version}
    done
}

if [ "$1" == "-h" ]
then
    tput setaf 2
    echo "Usage:" $(basename $0)
    echo "    -h - this command"
    echo "    53 - run tests for PHP 5.3 (command 'php53' is required)"
    echo "    without arguments - run tests for all supported php versions"
    echo -n "Supported php versions: 53, 54, 55, 56, 70, 71, default."
    resetTextStyle
    echo ""
    exit 0
fi

if [ -n "$1" ]
then
    v=$1
    if [[ "$v" == "default" ]]
    then
        v=$(php -r "echo PHP_MAJOR_VERSION, PHP_MINOR_VERSION;")
    fi
    printInfo "StackTrace                  "
    printInfo "Running tests for version $v"
    echo ""
    echo ""
    runTestsFor ${v}
else
    printInfo "StackTrace                    "
    printInfo "Running tests for all versions"
    echo ""
    echo ""
    runTestsForAll
fi