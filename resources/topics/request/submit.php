<?php
session_start();

$url = 'resources/topics/request';

// Verify the form
$name = $_POST['name'];
$onid = $_SESSION['onid'];
$email = $_SESSION['email'];
$seminar = $_POST['seminar'];
$topic = htmlentities($_POST['topic']);

if (!$name) fail('Please include your name', $url);
if (!$onid || !$email) fail('ONID or email missing. Please log in.', $url);
if (!$seminar) fail('Please enter the name of the seminar you led', $url);
if (!$topic) fail('Please include the topic you would like to add', $url);

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
    fail('Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator', $url);
}


header('Location: ' . BASE_URL . $url . '/?sent=true');
