<?php

$rtid = $_POST['id'];

if(empty($rtid) || $rtid == '') {
    http_response_code(500);
    die();
}

try {
    // First we have to delete all of the 'resource for' relations
    $sql = 'DELETE FROM iota_resource_for WHERE rtid = :id';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
    $prepared->execute();

    // Finally we delete the resource topic
    $sql = 'DELETE FROM iota_resource_topic WHERE rtid = :id';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
    $prepared->execute();
} catch(PDOException $e) {
    $logger->error($e->getMessage());
    http_response_code(500);
}

http_response_code(200);