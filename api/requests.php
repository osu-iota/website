<?php
setApiErrorConfigForThisFile();
include_once PUBLIC_FILES . '/lib/authorize.php';
include_once PUBLIC_FILES . '/lib/rest-utils.php';
include_once PUBLIC_FILES . '/lib/email.php';

// Define handlers
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        $body = readRequestBodyJson();

        // Verify the form
        $type = $body['type'];
        if($type . '' == '') respond(400, 'Please include the type of request being made');

        if($type == 'contribution') {
            allowIf($userIsLoggedIn);
            makeContributionAccessRequest($body);
        } elseif ($type == 'topic') {
            allowIf($userIsContributor);
            makeTopicAdditionRequest($body);
        }

        break;

    default:
        respond(400, 'Invalid request on contribution access request resource');
}

function makeContributionAccessRequest($body) {
    global $config, $user, $logger;

    $name = $body['name'];
    $onid = $user->getOnid();
    $email = $user->getEmail();
    $seminar = $body['seminar'];
    $role = htmlentities($body['role']);

    if (!$name) respond(400, 'Please include your name');
    if (!$seminar) respond(400, 'Please enter the name of the seminar you led');
    if (!$role) respond(400, 'Please provide details about your role in the seminar');

    $subject = 'Contribution Access Request';

    // Construct the message body
    $message = "<p>IOTA Administrator,</p>";
    $message .= "<p>The following individual has requested permission to contribute to the resource repository.</p>";
    $message .= "<p><strong>Name:</strong> $name</p>";
    $message .= "<p><strong>Email:</strong> $email</p>";
    $message .= "<p><Strong>ONID:</Strong> $onid</p>";
    $message .= "<p><strong>Seminar:</strong> $seminar</p>";
    $message .= "<p><strong>Role:</strong> $role</p>";

    // Get list of emails to send the contact form to
    $to = $config['admin']['emails'];
    $from = $config['admin']['serverEmail'];

    // Send and check for errors
    $error = sendEmail($subject, $message, $to, $from);

    if ($error) {
        $logger->error('Error sending email: ' . $error);
        respond(500, 'Failed to send contribution access request message');
    }

    respond(200, 'Message sent successfully');
}

function makeTopicAdditionRequest($body) {
    global $config, $user, $logger;

    $name = $user->getName();
    $onid = $user->getOnid();
    $email = $user->getEmail();
    $seminar = $body['seminar'];
    $topic = htmlentities($body['topic']);

    if ($name . '' == '') respond(400, 'Please include your name');
    if ($onid . '' == '' || $email . '' == '') respond(400, 'ONID or email missing. Please log in.');
    if ($seminar . '' == '') respond(400, 'Please enter the name of the seminar you led');
    if ($topic . '' == '') respond(400, 'Please include the topic you would like to add');

    $subject = 'Topic Addition Request';

    $message = "<p>IOTA Administrator,</p>";
    $message .= "<p>A request for an additional topic to be added to the IOTA resource repository has been submitted. See below for details:</p>";
    $message .= "<p><strong>Name:</strong> $name</p>";
    $message .= "<p><strong>Email:</strong> $email</p>";
    $message .= "<p><Strong>ONID:</Strong> $onid</p>";
    $message .= "<p><strong>Seminar:</strong> $seminar</p>";
    $message .= "<p><strong>Topic: $topic</strong></p>";

    // Get list of emails to send the contact form to
    $to = $config['admin']['emails'];
    $from = $config['admin']['serverEmail'];

    // Send and check for errors
    $error = sendEmail($subject, $message, $to, $from);

    if ($error) {
        $logger->error('Error sending email: ' . $error);
        respond(500, 'Failed to send topic addition request message');
    }

    respond(200, 'Message sent successfully');
}