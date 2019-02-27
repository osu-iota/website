<?php

include_once BASE . '/lib/rest-utils.php';

allowIf($userIsAdmin);

$body = readRequestBodyJson();

$rtid = $body['id'];
$name = htmlentities($body['name']);

if ($rtid . '' == '') respond(400, 'Please include id of topic to update in request');
if ($name . '' == '') respond(400, 'Please include a valid name to update the topic to');

try {
    $sql = 'UPDATE iota_resource_topic SET rt_name = :name WHERE rtid = :id';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':name', $name, PDO::PARAM_STR);
    $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
    $prepared->execute();
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    respond(500, 'Failed to update topic: an internal error occurred');
}

respond(200, 'Successfully updated topic to "' . $name . '"', $body);
