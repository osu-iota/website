<?php
ini_set('display_errors', 0);
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';
include_once PUBLIC_FILES . '/lib/users-html.php';

allowIf($userIsAdmin);

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        addNewUser();
        break;

    case 'PATCH':
        updateUser();
        break;

    case 'GET':
        getUsers();
        break;

    default:
        respond(400, 'Invalid request on users resource');

}

function addNewUser() {
    global $logger, $daoUsers;

    $body = readRequestBodyJson();

    $onid = strtolower($body['onid']);
    $level = $body['privLevel'];

    if (empty($onid) || $onid == '' || (empty($level) && $level != 0) || !is_numeric($level)) {
        $logger->error('Bad request when attempting to add new user with ONID ' . $onid . ' and level ' . $level);
        respond(400, 'Please include the onid and a numeric level for the new user');
    }

    $newUser = new \Model\User();
    $newUser->setOnid($onid);
    $newUser->setPrivilegeLevel($level);
    $ok = $daoUsers->createUser($newUser);

    if (!$ok) {
        respond(500, 'Failed to create new user');
    }

    respond(201, 'Successfully created new user');
}

function updateUser() {
    global $db, $logger;

    $query = readQueryString();

    $body = readRequestBodyJson();

    // Currently can only update the privilege level
    $id = $query['id'];
    $level = $body['level'];

    if((empty($level) && $level != 0) || empty($id)) {
        $logger->error('Cannot update user level without user id and level in request body');
        respond(400, 'Please include user id and new level');
    }

    try {
        $sql = 'UPDATE iota_user SET u_privilege_level = :level WHERE uid = :id';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':level', $level, PDO::PARAM_INT);
        $prepared->bindParam(':id', $id, PDO::PARAM_STR);
        $prepared->execute();
    } catch(Exception $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to update user');
    }

    respond(200, 'Successfully updated user');
}

function getUsers() {
    global $db, $logger;

    $query = readQueryString();

    // Get all the users
    $users = array();
    try {
        $sql = 'SELECT * FROM iota_user ORDER BY u_onid'; // TODO: implement LIMIT for large amount of users
        $users = $db->query($sql);
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to retrieve users information');
    }


    $body = array();

    if ($query['format'] == 'html') {
        $body['users'] = getUsersHtml($users);
    } else {
        $body['users'] = $users;
    }

    respond(200, 'Successfully retrieved user information', $body);
}