# Tests

StackTrace is fully tested on versions of PHP between 5.3 and 7.1.
This guide describes how to execute tests.

## Table of contents

1. [Production environment](#production-environment)
2. [Local environment with docker](#local-environment-with-docker)
3. [HHVM](#hhvm)

## Production environment

If you want to execute tests on your production environment,
execute the following commands on root directory of this project:

```bash
composer update --dev
vendor/bin/phpunit
```

### Requirements

* [composer](https://getcomposer.org/)
* `php ^5.3 || ^7.0`

## Local environment with docker

The following command executes test on your local computer.
Dependencies will be installed automatically in `build` directory.
Tests will be executed on all available versions of PHP.
You will need docker image [`splitbrain/phpfarm:jessie`](https://github.com/splitbrain/docker-phpfarm).
Image will be installed automatically if does not exist on your local computer.

```bash
bin/local-tests-docker.sh
```

If you want to execute test on specified version of PHP,
add optional argument `VERSION`.

```bash
VERSION=7.0
bin/local-tests-docker.sh ${VERSION}
```

Argument `VERSION` can be equal to one of below values:

* `5.3`
* `5.4`
* `5.5`
* `5.6`
* `7.0`
* `7.1`

### Requirements

* [docker](https://www.docker.com/)
* `php ^5.3 || ^7.0`

## HHVM

* Tests - `bin/hhvm-tests.sh`
* Interpreter - `bin/hhvm.sh`

### Requirements

* [docker](https://www.docker.com/)
