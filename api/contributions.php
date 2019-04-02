<?php
setApiErrorConfigForThisFile();
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';

allowIf($userIsContributor);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST' :
        $logger->info('POST request made on resource data resource');
        $body = readRequestBodyUrlFormEncoded();

        $action = $body['action'];

        if ($action . '' == '') {
            respond(400, 'Please include action in POST body');
        }

        if ($action == 'add') {
            addNewContribution($body);
        } elseif ($action == 'update') {
            $query = readQueryString();
            $rid = $query['id'];
            if ($rid . '' == '') {
                respond(400, 'Must include the resource id to edit content');
            }

            updateContributionData($rid, $body);
        } else {
            respond(400, 'Invalid action requested');
        }

        break;

    default:
        $logger->info("Invalid action $method on resource data");
        respond(400, 'Invalid request on resource data');
}

/**
 * Creates a new contribution of resource repository materials.
 * 
 * This function inserts metadata into the database and associates the contribution with the session user. It also
 * saves the uploaded file content under the private data directory and records file information in the database.
 *
 * @param mixed[] $body the parsed body of the POST request
 * @return void
 */
function addNewContribution($body) {
    global $user, $db, $logger;

    $logger->info('Request to add new resource contribution accepted. Validating...');

    $uid = $user->getId();
    $name = htmlentities($body['name']);
    $description = htmlentities($body['description']);
    $topics = $body['topics'];
    $resource = $body['resource'];

    $error = false;

    if ($name . '' == '') {
        $error = 'Please include a name for the resource';
    }
    if ($description . '' == '') {
        $error = $error ? $error . ', a description' : 'Please include a description for the resource';
    }
    if ($topics . '' == '' || count($topics) == 0) {
        $error = $error ? $error . ', at least one topic' : 'Please include at least one topic';
    }
    if ($resource . '' == '' || $resource['size'] == 0) {
        $error = $error ? $error . ', a resource file' : 'Please include a resource file';
    }

    if ($error) {
        $logger->info("Invalid request: $error");
        respond(400, $error);
    }

    // Check file size (10 MB limit)
    if ($resource['size'] > 10000000) {
        $logger->info('Invalid request: uploaded file size too large');
        respond(400, 'File size is too large. File must be smaller than 10 MB');
    }

    // Everything looks good
    $logger->info('Request validated. Processing request...');
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

    $logger->info('Request processed successfully');
    respond(201, 'Successfully submitted contribution config');
}

/**
 * Update the metadata and (optionally) file data associated with an existing resource material contribution.
 * 
 * This function updates metadata on existing resource data entries in the database. If a new file is provided as an
 * upload, the function also creates a new resource data entry and marks that entry as the active entry in the
 * database table. This action can be rolled back by an admin.
 *
 * @param string $rid the id of the resource to update
 * @param mixed[] $body the parsed body of the POST request containing updated resource data
 * @return void
 */
function updateContributionData($rid, $body) {
    global $user, $db, $logger;

    $logger->info('Request to update existing contribution data accepted. Validating...');

    $uid = $user->getId();
    $name = htmlentities($body['name']);
    $description = htmlentities($body['description']);
    $topics = $body['topics'];
    $resource = $body['resource'];

    $error = false;

    if ($name . '' == '') {
        $error = 'Please include a name for the resource';
    }
    if ($description . '' == '') {
        $error = $error ? $error . ', a description' : 'Please include a description for the resource';
    }
    if ($topics . '' == '' || count($topics) == 0) {
        $error = $error ? $error . ', at least one topic' : 'Please include at least one topic';
    }

    if ($error) {
        $logger->info("Invalid request: $error");
        respond(400, $error);
    }


    // Everything looks good
    $logger->info('Request validated. Processing...');
    $db->beginTransaction();
    try {

        // Get the rdid of the currently active resource data
        $sql = 'SELECT rdid FROM iota_resource_data WHERE rid = :rid AND rd_active = TRUE';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $prepared->execute();
        $result = $prepared->fetchAll();
        $oldRdid = $result[0]['rdid'];

        // Add the resource config if the user uploaded a new file
        if ($resource . '' != '' && $resource['size'] > 0) {
            // Check file size (10 MB limit)
            if ($resource['size'] > 10000000) {
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
            $sql = 'INSERT INTO iota_resource_data ';
            $sql .= 'VALUES(:rdid, :rid, :rdext, :rdmime, :rddate, :rddownloads, :rdactive)';
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

    $logger->info('Request completed successfully');
    respond(200, 'Successfully updated contribution information');
}
