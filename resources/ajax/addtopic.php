<?php

include_once BASE . '/lib/rest-utils.php';

allowIf($userIsAdmin);

$body = readRequestBodyJson();

$topic = htmlentities($body['topic']);

if (!$topic) respond(400, 'Please include topic in request');


$sql = 'INSERT INTO iota_resource_topic VALUES(:id, :topic)';
$prepared = $db->prepare($sql);
$rtid = \OSU\IOTA\Util\Security::generateSecureUniqueId();
$prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
$prepared->bindParam(':topic', $topic, PDO::PARAM_STR);
try {
    $prepared->execute();
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    respond(500, 'Failed to add topic "' . $topic . '": an internal error occurred.');
}

respond(201, 'Successfully created new topic "' . $topic . '"');

