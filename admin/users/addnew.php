<?php
if (!$userIsAdmin) {
    http_response_code(401);
    die();
}

$onid = strtolower($_POST['onid']);
$level = $_POST['privLevel'];

if (empty($onid) || $onid == '' || (empty($level) && $level != 0) || !is_numeric($level)) {
    $logger->error('Bad request when attempting to add new user with ONID ' . $onid . ' and level ' . $level);
    http_response_code(400);
    die();
}

$newUser = new \OSU\IOTA\Model\User();
$newUser->setOnid($onid);
$newUser->setPrivilegeLevel($level);
$ok = $daoUsers->createUser($newUser);

if (!$ok) {
    http_response_code(500);
    die();
}

http_response_code(201);
