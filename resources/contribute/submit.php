<?php
session_start();

$url = 'resources/contribute';

$uid = $_SESSION['uid'];
$name = $_POST['name'];
$description = $_POST['description'];
$topics = $_POST['topics'];
$resource = $_FILES['resource'];

if (!$uid) fail($url, 'Please log in to contribute content');
if (!$name) fail($url, 'Please include a name for the resource');
if (!$description) fail($url, 'Please include a description for the resource');
if (!$topics || count($topics) == 0) fail($url, 'Please associate the resource with at least one topic');
if (!$resource) fail($url, 'You must include a resource file');

// Everything looks good
$db->beginTransaction();
try {
    // Create a new resource entry
    $rid = \OSU\IOTA\Util\Security::generateSecureUniqueId();
    $sql = 'INSERT INTO iota_resource VALUES(:id, :rname, :rdescription)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':id', $rid, PDO::PARAM_STR);
    $prepared->bindParam(':rname', $name, PDO::PARAM_STR);
    $prepared->bindParam(':rdescription', $description, PDO::PARAM_STR);
    $prepared->execute();

    // Add the resource data
    $rdid = \OSU\IOTA\Util\Security::generateSecureUniqueId();
    $ext = pathinfo($resource['name'], PATHINFO_EXTENSION);
    $infofd = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($infofd, $resource['tmp_name']);
    finfo_close($infofd);
    $fd = fopen($resource['tmp_name'], 'rb');
    $downloads = 0;
    $active = true;
    $sql = 'INSERT INTO iota_resource_data VALUES(:rdid, :rid, :rddata, :rdext, :rdmime, :rddate, :rddownloads, :rdactive)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
    $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
    $prepared->bindParam(':rddata', $fd, PDO::PARAM_LOB);
    $prepared->bindParam(':rdext', $ext, PDO::PARAM_STR);
    $prepared->bindParam(':rdmime', $mime, PDO::PARAM_STR);
    $prepared->bindParam(':rddate', time(), PDO::PARAM_INT);
    $prepared->bindParam(':rddownloads', $downloads, PDO::PARAM_INT);
    $prepared->bindParam(':rdactive', $active, PDO::PARAM_BOOL);
    $prepared->execute();

    // Add the topic relations
    $sql = 'INSERT INTO iota_resource_for VALUES(:rid, :rtid)';
    $prepared = $db->prepare($sql);
    foreach ($topics as $topic) {
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->bindParam(':rtid', $topic, PDO::PARAM_STR);
        $prepared->execute();
    }

    // Add a relation between the user and the committed resource data
    $sql = 'INSERT INTO iota_contributes VALUES(:uid, :rdid, :cndate)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
    $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
    $prepared->bindParam(':cndate', time(), PDO::PARAM_INT);
    $prepared->execute();

    // Commit the transaction
    $db->commit();

    // Everything worked
    header('Location: ' . BASE_URL . $url . '/?submitted=true');

} catch (Exception $e) {
    $logger->error($e->getMessage());
    $db->rollBack();
    fail($url, 'Failed to save resource. If you continue to experience problems, please contact an IOTA site administrator');
}


