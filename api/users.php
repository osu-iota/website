<?php
/**
 * The resource endpoint for handling asynchronous requests made on user resources.
 */
setApiErrorConfigForThisFile();
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';
include_once PUBLIC_FILES . '/lib/users-html.php';

allowIf($userIsAdmin);

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $body = readRequestBodyJson();
        addNewUser($body);
        break;

    case 'PATCH':
        $query = readQueryString();
        $body = readRequestBodyJson();
        $id = $query['id'];

        if ($id . '' == '') {
            $logger->info('Invalid request: missing ID of user to update');
            respond(400, 'Please include ID of user to update in query string');
        }

        updateUser($id, $body);
        break;

    case 'GET':
        getUsers();
        break;

    default:
        respond(400, 'Invalid request on users resource');

}

/**
 * Creates a new user in the database.
 *
 * @param mixed[] $body the parsed request body containing new user information
 * @return void
 */
function addNewUser($body) {
    global $logger, $daoUsers;

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

/**
 * Updates information associated with an existing user in the database.
 *
 * @param string $id the ID of the user to update
 * @param mixed[] $body the parsed request body containing data to update the user with
 * @return void
 */
function updateUser($id, $body) {
    global $db, $logger;

    // Currently can only update the privilege level
    $level = $body['level'];

    if ((empty($level) && $level != 0)) {
        $logger->error('Cannot update user level without level in request body');
        respond(400, 'Please include the new level');
    }

    try {
        $sql = 'UPDATE iota_user SET u_privilege_level = :level WHERE uid = :id';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':level', $level, PDO::PARAM_INT);
        $prepared->bindParam(':id', $id, PDO::PARAM_STR);
        $prepared->execute();
    } catch (Exception $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to update user');
    }

    respond(200, 'Successfully updated user');
}

/**
 * Fetches all existing users in the database
 *
 * @return void
 */
function getUsers() {
    global $db, $logger;

    $query = readQueryString();

    // Get all the users
    $users = array();
    try {
        $sql = 'SELECT * FROM iota_user ORDER BY u_onid'; // TODO: implement LIMIT for large amount of users
        $prepared = $db->prepare($sql);
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $prepared->execute();
        $users = $prepared->fetchAll();
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
