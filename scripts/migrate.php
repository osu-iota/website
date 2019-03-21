<?php
/**
 * THIS FILE HAS ALREADY BEEN USED TO MIGRATE THE DATA FROM THE OLD DB SCHEMA TO THE NEW. DO NOT USE AGAIN.
 *
 * NOTE: This file is NOT to be run from the web server. It should be run locally on a development machine against
 * a local MySQL server that already has the old config imported. This script should only be executed once, and the
 * inclusion of the script in source control is merely for historical purposes. After executing, ensure that the read
 * permissions are restricted only to the current user so that it cannot be run by a web-server process.
 *
 * ASSUMPTIONS:
 * 1) The config has never been migrated into the new schema before
 * 2) There exists a sub-directory 'participates-config' from the location the script is executed
 */

// Uncomment for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dbHost = '127.0.0.1';
$dbName = 'iota';
$dbUser = 'root';
$dbPassword = ''; // include the password here, but do not check it in to source control
$url = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
$db = new PDO($url, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the class used to generate IDs
require '../lib/OSU/IOTA/Util/Security.php';

# First, get all the old config
$sql = 'SELECT * FROM user, participation, participates ';
$sql .= 'WHERE user.uid = participates.uid AND participates.pid = participation.pid ';
$sql .= 'ORDER BY user.uid';
$users = $db->query($sql)->fetchAll();


$last = null;
$uid = null;
foreach ($users as $user) {
    if ($last == $user['uid']) {
        // The same user, add another entry for them in either participates or contributes
        insertNewParticipation($db, $user, $uid);
    } else {
        // We have a new user, generate a new id and create an entry for them
        echo 'Migrating config for user ' . $user['onid'] . "\n";
        $uid = insertNewUser($db, $user);
        insertNewParticipation($db, $user, $uid);
    }
    $last = $user['uid'];
}

/**
 * @param $db PDO
 * @param $row array
 * @return string
 */
function insertNewUser($db, $row) {
    $uid = \Security::generateSecureUniqueId();
    $privLevel = 0;
    $lastLogin = time();
    $sql = 'INSERT INTO iota_user VALUES(:uid, :uonid, :uprivlevel, :ulastlogin)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam('uid', $uid, PDO::PARAM_STR);
    $prepared->bindParam(':uonid', $row['onid'], PDO::PARAM_STR);
    $prepared->bindParam(':uprivlevel', $privLevel, PDO::PARAM_INT);
    $prepared->bindParam(':ulastlogin', $lastLogin, PDO::PARAM_INT);
    $prepared->execute();
    return $uid;
}

/**
 * @param $db PDO
 * @param $row array
 * @param $uid string
 */
function insertNewParticipation($db, $row, $uid) {
    // First create a participation config entry if we need to
    // All of the 'url' fields are empty at the time of this migration, so we do not consider them here. We only
    // look at selfies
    $pdid = null;
    if (!empty($row['selfie']) && $row['selfie'] != '') {
        // There is an image to fetch and add to our database
        $selfie = $row['selfie'];
        $extPieces = explode(".", $selfie);
        $extension = end($extPieces);
        $mime = 'image/' . $extension;
        $pdid = \Security::generateSecureUniqueId();
        $sql = 'INSERT INTO iota_participates_data VALUES (:pdid, :extension, :mime)';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':pdid', $pdid, PDO::PARAM_STR);
        $prepared->bindParam(':extension', $extension, PDO::PARAM_STR);
        $prepared->bindParam(':mime', $mime, PDO::PARAM_STR);
        $prepared->execute();
        // Put the file in the participates-config directory
        $destination = 'participates-config/' . $pdid . '.' . $extension;
        file_put_contents($destination, fopen($selfie , 'r'));
    }
    $pid = \Security::generateSecureUniqueId();
    $sql = 'INSERT INTO iota_participates VALUES (:pid, :uid, :name, :type, :club, :description, :config)';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':pid', $pid, PDO::PARAM_STR);
    $prepared->bindParam(':uid', $uid, PDO::PARAM_STR);
    $prepared->bindParam(':name', $row['event_name'], PDO::PARAM_STR);
    $prepared->bindParam(':type', $row['participation_type'], PDO::PARAM_STR);
    $prepared->bindParam(':club', $row['club_name'], PDO::PARAM_STR);
    $prepared->bindParam(':description', $row['description'], PDO::PARAM_STR);
    $prepared->bindParam(':config', $pdid, PDO::PARAM_STR);
    $prepared->execute();
}