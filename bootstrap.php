<?php

// Uncomment to print PHP error messages before config loads
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

define('PUBLIC_FILES', __DIR__);

include_once PUBLIC_FILES . '/lib/utils.php';
include_once PUBLIC_FILES . '/lib/init-config.php';

// Define some global constants we can use in other scripts that capture some important config settings
define('PRIVATE_FILES', $config['server']['privateFiles']);
define('BASE_URL', $config['client']['baseUrl']);
define('PARTICIPATES_DATA_DIR', PRIVATE_FILES . '/data/participates-data');
define('RESOURCE_DATA_DIR', PRIVATE_FILES . '/data/resource-data');
define('DEVELOPMENT_MODE', $config['mode'] == 'development');

// Set an autoloader for custom classes
spl_autoload_register(function ($className) {
    include PUBLIC_FILES . '/lib/classes/' . str_replace('\\', '/', $className) . '.php';
});

// Load some other useful globals
include_once PUBLIC_FILES . '/lib/init-logger.php';
include_once PUBLIC_FILES . '/lib/init-database.php';
include_once PUBLIC_FILES . '/lib/init-user.php';

// Define helpers variables for including CSS and JavaScript files and altering what files are included page by page
$css = array();
$js = array();
