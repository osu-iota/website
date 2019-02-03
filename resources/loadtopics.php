<?php
include_once '.meta.php';

$sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
try {
    $res = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

foreach ($res as $topic) {
    echo '<tr>';
    echo '<td>' . $topic['rt_name'] . '</td>';
    echo '</tr>';
}