<?php
session_start();

include_once BASE . '/lib/rest-utils.php';

$request = readRequestBodyJson();

// Verify the form
if(!$user)  respond(400, 'Please log in');

$name = $user->getName();
$onid = $user->getOnid();
$email = $user->getEmail();
$seminar = $request['seminar'];
$topic = htmlentities($request['topic']);

if ($name . '' == '') respond(400, 'Please include your name');
if ($onid . '' == '' || $email . '' == '') respond(400, 'ONID or email missing. Please log in.');
if ($seminar . '' == '') respond(400, 'Please enter the name of the seminar you led');
if ($topic . '' == '') respond(400, 'Please include the topic you would like to add');

$from = 'From: IOTA Alliance <no-reply@iota.engr.orst.edu>';
$subject = '[IOTA] Topic Addition Request';

$toEmails = BASE . '/include/data/contact-us-emails.json';
$to = implode(',', json_decode(file_get_contents($toEmails), true));

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= $from . "\r\n";

$message = "
<html>
<head>
<title>Topic Addition Request</title>
</head>
<body>
<p>IOTA Administrator,</p>
<p>A request for an additional topic to be added to the IOTA resource repository has been submitted. See below for details:</p>
<p><strong>Name:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><Strong>ONID:</Strong> $onid</p>
<p><strong>Seminar:</strong> $seminar</p>
<p><strong>Topic: $topic</strong></p>
</body>
</html>
";

try {
    mail($to, $subject, $message, $headers);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    respond(500, 'Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator');
}

respond(200, 'Successfully requested topic', array("topic" => $topic));
