<?php

$pdid = $_GET['pd'];

// Read the blob out of the database and stream it
$sql = 'SELECT * FROM iota_participates_data, iota_participates WHERE pdid = :id AND p_data IS NOT NULL AND p_data = pdid';
$prepared = $db->prepare($sql);
$prepared->bindParam(':id', $pdid, PDO::PARAM_STR);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$result = $prepared->fetchAll();
if (count($result) == 0) {
    die();
}
$data = $result[0];

// Send the config back
$filename = strtolower(str_replace(' ', '-', $data['p_type'])) . '.' . $data['pd_extension'];
//header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Type: ' . $data['pd_mime']);
$bytes = readfile(PARTICIPATES_DATA_DIR . '/' . $pdid . '.' . $data['pd_extension']);
if (!$bytes) {
    fail('Failed to display participate config. If the problem persists, please use the "Contact Us" form to contact the ' .
        'IoT Alliance website administrators.');
}