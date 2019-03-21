<?php
ini_set('display_errors', 0);
include_once BASE . '/lib/authorize.php';
include_once BASE . '/lib/rest-utils.php';
include_once BASE . '/lib/topic-html.php';

// Define handlers
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        getTopics();
        break;

    case 'POST':
        allowIf($userIsAdmin);
        addNewTopic();
        break;

    case 'PATCH':
        allowIf($userIsAdmin);
        updateTopic();
        break;

    case 'DELETE':
        allowIf($userIsAdmin);
        deleteTopic();
        break;

    default:
        respond(400, 'Invalid request on participation config');
}

function getTopics() {
    global $db, $logger;
    $sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
    try {
        $res = $db->query($sql);
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to load topics');
    }

    $body = array('topics' => $res);

    $query = readQueryString();

    if ($query['format'] == 'html') {
        $body['html'] = getTopicHtml($res);
    }

    respond(200, 'Successfully fetched topic config', $body);

}

function addNewTopic() {

    global $db, $logger;
    $body = readRequestBodyJson();

    $topic = htmlentities($body['topic']);

    if (!$topic) respond(400, 'Please include topic in request');

    $sql = 'INSERT INTO iota_resource_topic VALUES(:id, :topic)';
    $prepared = $db->prepare($sql);
    $rtid = \Security::generateSecureUniqueId();
    $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
    $prepared->bindParam(':topic', $topic, PDO::PARAM_STR);
    try {
        $prepared->execute();
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to add topic "' . $topic . '": an internal error occurred.');
    }

    respond(201, 'Successfully created new topic "' . $topic . '"');
}

function deleteTopic() {

    global $db, $logger;
    $body = readRequestBodyJson();

    $rtid = $body['id'];

    if ($rtid . '' == '') respond(400, 'Please include ID of topic to delete in request');

    try {
        $db->beginTransaction();

        // First we have to delete all of the 'resource for' relations
        $sql = 'DELETE FROM iota_resource_for WHERE rtid = :id';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
        $prepared->execute();

        // Finally we delete the resource topic
        $sql = 'DELETE FROM iota_resource_topic WHERE rtid = :id';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
        $prepared->execute();

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        $logger->error($e->getMessage());
        respond(500, 'Failed to remove topic: an internal error occurred');
    }

    respond(200, 'Successfully removed topic');
}

function updateTopic() {
    global $db, $logger;
    $body = readRequestBodyJson();

    $rtid = $body['id'];
    $name = htmlentities($body['name']);

    if ($rtid . '' == '') respond(400, 'Please include id of topic to update in request');
    if ($name . '' == '') respond(400, 'Please include a valid name to update the topic to');

    try {
        $sql = 'UPDATE iota_resource_topic SET rt_name = :name WHERE rtid = :id';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':name', $name, PDO::PARAM_STR);
        $prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
        $prepared->execute();
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        respond(500, 'Failed to update topic: an internal error occurred');
    }

    respond(200, 'Successfully updated topic to "' . $name . '"', $body);
}