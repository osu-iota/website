<?php

$rtid = $_POST['id'];
$name = $_POST['name'];

if(empty($rtid) || $rtid == '' || empty($name) || $name == '') {
    $logger->error('Attempted to update topic with malformed request: rtid = ' . $rtid . ', name = ' . $name);
    http_response_code(500);
    die();
}

try {
    $sql = 'UPDATE iota_resource_topic SET rt_name = :name WHERE rtid = :id';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':name', $name, PDO::PARAM_STR);
    $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
    $prepared->execute();
} catch(PDOException $e) {
    $logger->error($e->getMessage());
    http_response_code(500);
}

http_response_code(200);