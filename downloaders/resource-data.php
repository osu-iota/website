<?php

$rdid = $_GET['r'];

// Read the blob out of the database and stream it
$sql = 'SELECT * FROM iota_resource_data rd, iota_resource r WHERE rd.rdid = :id AND rd.rid = r.rid AND rd.rd_active = 1';
$prepared = $db->prepare($sql);
$prepared->bindParam(':id', $rdid, PDO::PARAM_STR);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$result = $prepared->fetchAll();
if (count($result) == 0) {
    die();
}
$data = $result[0];

// Send the config back
$filename = strtolower(str_replace(' ', '-', $data['r_name'])) . '.' . $data['rd_extension'];
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Type: ' . $data['rd_mime']);
$bytes = readfile(RESOURCE_DATA_DIR . '/' . $rdid . '.' . $data['rd_extension']);
if(!$bytes) {
    fail('Failed to upload resource file. If the problem persists, please use the "Contact Us" form to contact the '.
    'IoT Alliance website administrators.');
}

try {
    // Update the download count
    $sql = 'UPDATE iota_resource_data SET rd_downloads = rd_downloads + 1 WHERE rdid = :rdid';
    $prepared = $db->prepare($sql);
    $prepared->bindParam(':rdid', $rdid, PDO::PARAM_STR);
    $prepared->execute();
} catch(Exception $e) {
    $logger->error($e->getMessage());
}