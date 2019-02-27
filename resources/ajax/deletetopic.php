<?php

include_once BASE . '/lib/rest-utils.php';

allowIf($userIsAdmin);

$body = readRequestBodyJson();

$rtid = $body['id'];

if($rtid . '' == '') respond(400, 'Please include ID of topic to delete in request');

try {
    $db->beginTransaction();

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

    $db->commit();
} catch(Exception $e) {
    $db->rollBack();
    $logger->error($e->getMessage());
    respond(500, 'Failed to remove topic: an internal error occurred');
}

respond(200, 'Successfully removed topic');