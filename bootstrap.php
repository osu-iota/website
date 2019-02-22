<?php
session_start();
$reset = false;
if ($reset) {
    session_unset();
}

// Uncomment for debugging
// ini_set('display_errors', 1);
// error_reporting(E_WARNING);

// Define some globals
define('BASE', __DIR__);
define('BASE_PRIVATE', dirname(__DIR__));
define('BASE_URL', 'http://groups.engr.oregonstate.edu/IOTA/');
define('PARTICIPATES_DATA_DIR', BASE_PRIVATE . '/data/participates-data');
define('RESOURCE_DATA_DIR', BASE_PRIVATE . '/data/resource-data');

$userIsLoggedIn = !empty($_SESSION['onid']);
$userIsAdmin = false;
$userIsManager = false;
$userIsContributor = false;

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
$daoUsers = new \OSU\IOTA\DAO\UserDao($db);

// Load the user if it isn't already loaded
/** @var \OSU\IOTA\Model\User|null $user */
$user = null;
if($userIsLoggedIn) {
    $user = $daoUsers->getUserWithOnid($_SESSION['onid']);
    $user->setName($_SESSION['name']);
    $user->setEmail($_SESSION['email']);
    // Initialize some variables to easily track permissions
    $userIsAdmin = $user->getPrivilegeLevel() > 2;
    $userIsManager = $user->getPrivilegeLevel() > 1;
    $userIsContributor = $user->getPrivilegeLevel() > 0;
}

function fail($message, $redirect = 'error/') {
    $_SESSION['message'] = array(
        'content' => $message,
        'type' => 'error'
    );
    header('Location: ' . BASE_URL . $redirect);
    die();
}

function urlContains($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}

function dump($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

//echo '<pre>';
//print_r($db->query('SELECT version()')->fetchAll());
//echo '</pre>';

