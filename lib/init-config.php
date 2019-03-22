<?php

session_start();

// Read in the proper configuration
$config = yaml_parse(file_get_contents(PUBLIC_FILES . '/config/site.yaml'));

if ($config['mode'] == 'production') {
    $config = array_merge($config, yaml_parse(file_get_contents(PUBLIC_FILES . '/config/production.yaml')));
} elseif ($config['mode'] == 'development') {
    $config = array_merge($config, yaml_parse(file_get_contents(PUBLIC_FILES . '/config/development.yaml')));
}
// TODO: add a final else that results in displaying an error page to the client

if (!$config['server']['saveSession']) {
    session_unset();
}

if ($config['server']['displayErrors']) {
    ini_set('display_errors', 1);
    error_reporting(E_WARNING);
}