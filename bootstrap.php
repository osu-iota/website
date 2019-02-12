<?php
session_start();
$reset = false;
if($reset) {
    session_unset();
}

// Uncomment for debugging
ini_set('display_errors', 1);
error_reporting(E_WARNING);

// Define some globals
define('BASE', __DIR__);
define('BASE_PRIVATE', dirname(__DIR__));
define('BASE_URL', 'http://groups.engr.oregonstate.edu/IOTA/');
define('PARTICIPATES_DATA_DIR', BASE_PRIVATE . '/data/participates-data');
define('RESOURCE_DATA_DIR', BASE_PRIVATE . '/data/resource-data');

// Initialize some variables to easily track permissions
$userIsAdmin = $_SESSION['privilegeLevel'] > 2;
$userIsManager = $_SESSION['privilegeLevel'] > 1;
$userIsContributor = $_SESSION['privilegeLevel'] > 0;

// Set an autoloader for custom classes
spl_autoload_register(function ($className) {
    include BASE . '/lib/' . str_replace('\\', '/', $className) . '.php';
});

// Initialize a simple logger (nothing fancy)
$logger = new \OSU\IOTA\Util\Logger(BASE_PRIVATE . '/out.log');

// Read database credentials and open a PDO connection
$dbType = 'prod'; // can be 'dev' or 'prod' for development and production, respectively
$dbCredentials = parse_ini_file(BASE_PRIVATE . '/database.ini', true);
$dbHost = $dbCredentials[$dbType]['host'];
$dbName = $dbCredentials[$dbType]['name'];
$dbUser = $dbCredentials[$dbType]['user'];
$dbPassword = $dbCredentials[$dbType]['password'];
$url = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
$db = new PDO($url, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function fail($message, $redirect = 'error/') {
    $_SESSION['message'] = array(
        'content' => $message,
        'type' => 'error'
    );
    header('Location: ' . BASE_URL . $redirect);
    die();
}

//echo '<pre>';
//print_r($db->query('SELECT version()')->fetchAll());
//echo '</pre>';

