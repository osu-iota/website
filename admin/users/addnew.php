<?php

$onid = strtolower($_POST['onid']);
$level = $_POST['privLevel'];

if(empty($onid) || $onid == '' || (empty($level) && $level != 0) || !is_numeric($level)) {
    $logger->error('Bad request when attempting to add new user with ONID ' . $onid . ' and level ' . $level);
    http_response_code(400);
    die();
}

$uid = \OSU\IOTA\Util\Security::generateSecureUniqueId();

try {
    $sql = 'INSERT INTO iota_user VALUES(:uid, :onid, :privLevel, :lastLogin)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
    $prepared->bindParam(':onid', $onid, PDO::PARAM_STR);
    $prepared->bindParam(':privLevel', $level, PDO::PARAM_INT);
    $prepared->bindParam(':lastLogin', time(), PDO::PARAM_INT);
    $prepared->execute();

} catch(PDOException $e) {
    $logger->error($e->getMessage());
    http_response_code(500);
    die();
}

http_response_code(201);

