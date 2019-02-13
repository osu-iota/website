<?php

// Currently can only update the privilege level
$id = $_POST['id'];
$level = $_POST['level'];

if((empty($level) && $level != 0) || empty($id)) {
    $logger->error('Cannot update user level without user id and level in request body');
    http_response_code(400);
    die();
}

try {
    $sql = 'UPDATE iota_user SET u_privilege_level = :level WHERE uid = :id';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':level', $level, PDO::PARAM_INT);
    $prepared->bindParam(':id', $id, PDO::PARAM_STR);
    $prepared->execute();
} catch(Exception $e) {
    $logger->error($e->getMessage());
    http_response_code(500);
    die();
}

http_response_code(200);