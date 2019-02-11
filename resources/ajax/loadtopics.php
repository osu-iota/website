<?php
session_start();

$sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
try {
    $res = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    http_send_status(500);
    die();
}

foreach ($res as $topic) {
    echo '<tr>';
    echo '<td><a href="resources/topics/?t=' . $topic['rtid'] . '">' . $topic['rt_name'] . '</a></td>';
    echo '<td></td>';
    echo '</tr>';
}