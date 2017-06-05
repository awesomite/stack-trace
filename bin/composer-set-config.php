#!/usr/bin/env php
<?php

global $argv;

$handle = function ($scriptName, $key, $value) {
    $jsonPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'composer.json';
    $json = json_decode(file_get_contents($jsonPath), true);
    if (!isset($json['config'])) {
        $json['config'] = array();
    }
    $json['config'][$key] = $value;
    file_put_contents($jsonPath, json_encode($json));
};

call_user_func_array($handle, $argv);
