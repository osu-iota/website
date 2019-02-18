<?php

use OSU\IOTA\Util\Security;

session_start();

$url = 'participate/';

$uid = $_POST['uid'];
$onid = strtolower($_POST['onid']);
$type = $_POST['type'];
$event = htmlentities($_POST['event']);
$club = $_POST['club'];
$selfie = $_FILES['selfie'];
$description = htmlentities($_POST['description']);


// Verify the uid and the onid are included
if (!$uid || !$onid) fail('Missing required session information. Please log in to submit participation data.', $url);

// Verify a participation type was selected
if (!$type) fail('Please select a participation type.', $url);

// If the participation type was an event, verify that the club, event name, and selfie are included
if ($type == 'event' && (!$club || !$event || !$selfie)) fail('Please include the event name, club, and a selfie in your submission', $url);

// If the participation type was an meeting, verify that the club and selfie are included
if ($type == 'meeting' && (!$club || $selfie)) fail('Please include the club whose meeting you attended and a selfie with your submission', $url);

// Verify the description was included
if (!$description) fail('Please include a description', $url);

// Make sure the upload is actually an image
$check = getimagesize($selfie["tmp_name"]);
if ($check == false) {
    fail('The uploaded file is not an image. Please submit a selfie to receive participation credit.', $url);
}


// Process the data
switch ($type) {
    case 'project':
        $club = null;
        $selfie = null;
    case 'meeting':
        $event = null;
    default:
        break;
}

// If a selfie is included, add it first and record the inserted ID
$pdid = null;
if ($selfie) {
    $logger->debug('Selfie included with upload. Saving to database');
    $pdid = Security::generateSecureUniqueId();
    $fp = fopen($selfie['tmp_name'], 'rb');
    $sql = 'INSERT INTO iota_participates_data VALUES(:id, :pdata, :ext)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':id', $pdid, PDO::PARAM_STR);
    $prepared->bindParam(':pdata', $fp, PDO::PARAM_LOB);
    $prepared->bindParam(':ext', strtolower(pathinfo($selfie['name'], PATHINFO_EXTENSION)), PDO::PARAM_STR);
    try {
        $prepared->execute();
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        fail('Failed to save selfie image. Participation data was not saved.', $url);
    }
}

// Add the rest of the participation data
$pid = Security::generateSecureUniqueId();
$sql = 'INSERT INTO iota_participates VALUES(:pid, :uid, :name, :type, :club, :description, :pdata)';
$prepared = $db->prepare($sql);
$prepared->bindParam(':pid', $pid, PDO::PARAM_STR);
$prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
$prepared->bindParam(':name', $event, PDO::PARAM_STR);
$prepared->bindParam(':type', $type, PDO::PARAM_STR);
$prepared->bindParam(':club', $club, PDO::PARAM_STR);
$prepared->bindParam(':description', $description, PDO::PARAM_STR);
$prepared->bindParam(':pdata', $pdid);
try {
    $prepared->execute();
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    fail('Failed to save participation data. Please submit again or try later', $url);
}

header('Location: ' . BASE_URL . '/participate/?submitted=true');
