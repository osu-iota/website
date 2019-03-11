<?php
session_start();

include_once '../authorize.php';

$url = 'resources/contribute';

$uid = $user->getId();
$name = htmlentities($_POST['name']);
$description = htmlentities($_POST['description']);;
$topics = $_POST['topics'];
$resource = $_FILES['resource'];

if (!$uid) fail('Please log in to contribute content', $url);
if (!$name) fail('Please include a name for the resource', $url);
if (!$description) fail('Please include a description for the resource', $url);
if (!$topics || count($topics) == 0) fail('Please associate the resource with at least one topic', $url);
if (!$resource || $resource['size'] == 0) fail('You must include a resource file', $url);

// Check file size (10 MB limit)
if ($resource["size"] > 10000000) {
    fail('File size is too large. File must be smaller than 10 MB', $url);
}

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
    $downloads = 0;
    $active = true;

    // Write the file to the directory before putting metadata in the database
    $target = RESOURCE_DATA_DIR . '/' . $rdid . '.' . $ext;
    $uploaded = move_uploaded_file($resource['tmp_name'], $target);
    if (!$uploaded) {
        throw new Exception('Failed to upload the file associated with the resource');
    }

    // The upload was successful. Add the entry to the database.
    $sql = 'INSERT INTO iota_resource_data VALUES(:rdid, :rid, :rdext, :rdmime, :rddate, :rddownloads, :rdactive)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
    $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
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
    // TODO: if the file upload succeeds but the database fails, we need to roll back
    $logger->error($e->getMessage());
    $db->rollBack();
    fail('Failed to save resource. If you continue to experience problems, please contact an IOTA site administrator', $url);
}


