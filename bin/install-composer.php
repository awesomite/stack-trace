#!/usr/bin/env php
<?php

$destinationFile = 'composer-setup.php';
copy('https://getcomposer.org/installer', $destinationFile);
$checkSum = '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410';
if (hash_file('SHA384', 'composer-setup.php') === $checkSum) {
    echo 'Installer verified' . PHP_EOL;
    require_once $destinationFile;
    return;
}

throw new \RuntimeException('Installer corrupt');