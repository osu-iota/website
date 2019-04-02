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

if ($config['server']['errors']['display']) {
    $sev = $config['server']['errors']['severity'];
    switch($sev) {
        case 'notice':
            $sev = E_NOTICE;
            break;
        case 'warning':
            $sev = E_WARNING;
            break;
        case 'all':
            $sev = E_ALL;
            break;
        default:
            $sev = E_WARNING;
            break;
    }
    ini_set('display_errors', 1);
    error_reporting($sev);
}

function setApiErrorConfigForThisFile() {
    global $config;
    if(!$config['server']['errors']['displayApiErrors']) {
        ini_set('display_errors', 0);
    }
}
