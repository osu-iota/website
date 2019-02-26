<?php
if(!$userIsAdmin) {
    http_send_status(401);
    die();
}
$topic = htmlentities($_POST['topic']);

if (!$topic) {
    http_send_status(400);
    die();
}

$sql = 'INSERT INTO iota_resource_topic VALUES(:id, :topic)';
$prepared = $db->prepare($sql);
$rtid = \OSU\IOTA\Util\Security::generateSecureUniqueId();
$prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
$prepared->bindParam(':topic', $topic, PDO::PARAM_STR);
try {
    $prepared->execute();
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    http_send_status(500);
    die();
}

http_send_status(201);
die();

