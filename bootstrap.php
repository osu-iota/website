<?php
session_start();
// Uncomment for debugging
ini_set('display_errors', 1);
error_reporting(E_WARNING);

// Define some globals
define('BASE', __DIR__);
define('BASE_PRIVATE', dirname(__DIR__));
define('BASE_URL', 'http://groups.engr.oregonstate.edu/IOTA/');

// Initialize some variables to easily track permissions
$userIsAdmin = $_SESSION['privilegeLevel'] > 1;
$userIsContributor = $_SESSION['privilegeLevel'] > 0;

// Set an autoloader for custom classes
spl_autoload_register(function ($className) {
    include BASE . '/lib/' . str_replace('\\', '/', $className) . '.php';
});

// Initialize a simple logger (nothing fancy)
$logger = new \OSU\IOTA\Util\Logger(BASE_PRIVATE . '/out.log');

// Read database credentials and open a PDO connection
$dbType = 'dev'; // can be 'dev' or 'prod' for development and production, respectively
$dbCredentials = parse_ini_file(BASE_PRIVATE . '/database.ini', true);
$dbHost = $dbCredentials[$dbType]['host'];
$dbName = $dbCredentials[$dbType]['name'];
$dbUser = $dbCredentials[$dbType]['user'];
$dbPassword = $dbCredentials[$dbType]['password'];
$url = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
$db = new PDO($url, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function fail($redirect, $message) {
    $_SESSION['message'] = array(
        'content' => $message,
        'type' => 'error'
    );
    header('Location: ' . BASE_URL . $redirect);
    die();
}

