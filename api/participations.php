<?php
ini_set('display_errors', 0);
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';

allowIf($userIsLoggedIn);

// Define handlers
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        $body = readRequestBodyUrlFormEncoded();

        $uid = $user->getId();
        $onid = $user->getOnid();
        $type = $body['type'];
        $event = htmlentities($body['event']);
        $club = $body['club'];
        $selfie = $body['selfie'];
        $description = htmlentities($body['description']);

        // Verify a participation type was selected
        if ($type . '' == '')
            respond(200, 'Please select a participation type.');

        // If the participation type was an event, verify that the club, event name, and selfie are included
        if ($type == 'event' && ($club . '' == '' || $event . '' == '' || $selfie . '' == ''))
            respond(400, 'Please include the event name, club, and a selfie in your submission');

        // If the participation type was an meeting, verify that the club and selfie are included
        if ($type == 'meeting' && ($club . '' == '' || $selfie . '' == ''))
            respond(400, 'Please include the club whose meeting you attended and a selfie with your submission');

        // Verify the description was included
        if ($description . '' == '')
            respond(400, 'Please include a description', $url);

        // Make sure the upload is actually an image
        $check = getimagesize($selfie["tmp_name"]);
        if ($check == false) {
            respond(400, 'The uploaded file is not an image. Please submit a selfie to receive participation credit.');
        }

        // Process the config
        switch ($type) {
            case 'project':
                $club = null;
                $selfie = null;
            case 'meeting':
                $event = null;
            default:
                break;
        }

        try {
            $db->beginTransaction();
            $pdid = null;
            // If a selfie is included, add it first and record the inserted ID
            if ($selfie) {

                $logger->debug('Selfie included with upload. Saving to database');
                $pdid = \Security::generateSecureUniqueId();
                $ext = strtolower(pathinfo($selfie['name'], PATHINFO_EXTENSION));
                $infofd = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($infofd, $selfie['tmp_name']);
                finfo_close($infofd);

                // Write the file to the directory before putting metadata in the database
                $target = PARTICIPATES_DATA_DIR . '/' . $pdid . '.' . $ext;
                $uploaded = move_uploaded_file($selfie['tmp_name'], $target);
                if (!$uploaded) {
                    throw new Exception('Failed to upload the selfie');
                }

                // Add metadata for selfie image to the database
                $sql = 'INSERT INTO iota_participates_data VALUES(:id, :ext, :mime)';
                $prepared = $db->prepare($sql);
                $prepared->bindParam(':id', $pdid, PDO::PARAM_STR);
                $prepared->bindParam(':ext', $ext, PDO::PARAM_STR);
                $prepared->bindParam(':mime', $mime, PDO::PARAM_STR);
                try {
                    $prepared->execute();
                } catch (PDOException $e) {
                    $db->rollBack();
                    $logger->error($e->getMessage());
                    respond(500, 'Failed to save selfie image. Participation config was not saved.');
                }
            }

            // Add the rest of the participation config
            $pid = \Security::generateSecureUniqueId();
            $sql = 'INSERT INTO iota_participates VALUES(:pid, :uid, :name, :type, :club, :description, :pdata)';
            $prepared = $db->prepare($sql);
            $prepared->bindParam(':pid', $pid, PDO::PARAM_STR);
            $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
            $prepared->bindParam(':name', $event, PDO::PARAM_STR);
            $prepared->bindParam(':type', $type, PDO::PARAM_STR);
            $prepared->bindParam(':club', $club, PDO::PARAM_STR);
            $prepared->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared->bindParam(':pdata', $pdid);

            $prepared->execute();

            $db->commit();

        } catch (Exception $e) {
            $logger->error($e->getMessage());
            $db->rollBack();
            respond(500,'Failed to save participation config. Please submit again or try later');
        }

        respond(201, 'Successfully submitted participation information');

        break;

    default:
        respond(400, 'Invalid request on participation config');
}