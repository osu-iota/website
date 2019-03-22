<?php
ini_set('display_errors', 0);
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';

allowIf($userIsContributor);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST' :
        addNewContribution();
        break;

    case 'PUT':
        updateContributionData();
        break;

    default:
        respond(400, 'Invalid request on contribution config');
}

function addNewContribution() {
    global $user, $db, $logger;
    $body = readRequestBodyUrlFormEncoded();

    $uid = $user->getId();
    $name = htmlentities($body['name']);
    $description = htmlentities($body['description']);;
    $topics = $body['topics'];
    $resource = $body['resource'];

    if ($name . '' == '')
        respond(400, 'Please include a name for the resource');
    if ($description . '' == '')
        respond(400, 'Please include a description for the resource');
    if ($topics . '' == '' || count($topics) == 0)
        respond(400, 'Please associate the resource with at least one topic');
    if ($resource . '' == '' || $resource['size'] == 0)
        respond(400, 'You must include a resource file');

    // Check file size (10 MB limit)
    if ($resource["size"] > 10000000) {
        respond(400, 'File size is too large. File must be smaller than 10 MB');
    }

    // Everything looks good
    $db->beginTransaction();
    try {
        // Create a new resource entry
        $rid = \Security::generateSecureUniqueId();
        $sql = 'INSERT INTO iota_resource VALUES(:id, :rname, :rdescription)';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':id', $rid, PDO::PARAM_STR);
        $prepared->bindParam(':rname', $name, PDO::PARAM_STR);
        $prepared->bindParam(':rdescription', $description, PDO::PARAM_STR);
        $prepared->execute();

        // Add the resource config
        $rdid = \Security::generateSecureUniqueId();
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

        // Add a relation between the user and the committed resource config
        $sql = 'INSERT INTO iota_contributes VALUES(:uid, :rdid, :cndate)';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
        $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
        $prepared->bindParam(':cndate', time(), PDO::PARAM_INT);
        $prepared->execute();

        // Commit the transaction
        $db->commit();

    } catch (Exception $e) {
        // TODO: if the file upload succeeds but the database fails, we need to roll back
        $logger->error($e->getMessage());
        $db->rollBack();
        respond(500, 'Failed to save resource. If you continue to experience problems, please contact an IOTA site administrator');
    }

    respond(201, 'Successfully submitted contribution config');
}

function updateContributionData() {
    global $user, $db, $logger;

    $query = readQueryString();

    $body = readRequestBodyUrlFormEncoded();

    $uid = $user->getId();
    $rid = $query['id'];
    $name = htmlentities($body['name']);
    $description = htmlentities($body['description']);
    $topics = $body['topics'];
    $resource = $body['resource'];

    if ($rid . '' == '')
        respond(400, 'Must include the resource id to edit content');
    if ($name . '' == '')
        respond(400, 'Please include a name for the resource');
    if ($description . '' == '')
        respond(400, 'Please include a description for the resource');
    if ($topics . '' == '' || count($topics) == 0)
        respond(400, 'Please associate the resource with at least one topic');


    // Everything looks good
    $db->beginTransaction();
    try {

        // Get the rdid of the currently active resource data
        $sql = 'SELECT rdid FROM iota_resource_data WHERE rid = :rid AND active = TRUE';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $prepared->execute();
        $result = $prepared->fetchAll();
        $oldRdid = $result[0]['rdid'];

        // Add the resource config if the user uploaded a new file
        if ($resource . '' != '' && $resource['size'] > 0) {
            // Check file size (10 MB limit)
            if ($resource["size"] > 10000000) {
                respond(400, 'File size is too large. File must be smaller than 10 MB');
            }

            // Create a new resource config entry, be sure to set it to active and the old one to inactive
            $rdid = \Security::generateSecureUniqueId();
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

            // Unmark the previous config as the active resource
            $active = false;
            $sql = 'UPDATE iota_resource_data SET rd_active = :active WHERE rdid = :rdid';
            $prepared = $db->prepare($sql);
            $prepared->bindParam(':active', $active, PDO::PARAM_BOOL);
            $prepared->bindParam(':rdid', $oldRdid, PDO::PARAM_STR);
            $prepared->execute();

            // Add a relation between the user and the committed resource config
            $sql = 'INSERT INTO iota_contributes VALUES(:uid, :rdid, :cndate)';
            $prepared = $db->prepare($sql);
            $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
            $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
            $prepared->bindParam(':cndate', time(), PDO::PARAM_INT);
            $prepared->execute();
        }

        // Update the existing resource entry
        $sql = 'UPDATE iota_resource  SET r_name = :name, r_description = :description WHERE rid = :rid';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->bindParam(':name', $name, PDO::PARAM_STR);
        $prepared->bindParam(':description', $description, PDO::PARAM_STR);
        $prepared->execute();

        // Update the topic relations by first deleting all the old ones, then adding the new
        $sql = 'DELETE FROM iota_resource_for WHERE rid = :rid';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->execute();

        $sql = 'INSERT INTO iota_resource_for VALUES(:rid, :rtid)';
        $prepared = $db->prepare($sql);
        foreach ($topics as $topic) {
            $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
            $prepared->bindParam(':rtid', $topic, PDO::PARAM_STR);
            $prepared->execute();
        }

        // Commit the transaction
        $db->commit();

    } catch (Exception $e) {
        // TODO: if the file upload succeeds but the database fails, we need to roll back
        $logger->error($e->getMessage());
        $db->rollBack();
        respond(500, 'Failed to save resource. If you continue to experience problems, please contact an IOTA site administrator');
    }

    respond(200, 'Successfully updated contribution config information');
}