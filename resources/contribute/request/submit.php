<?php
session_start();

$url = 'resources/contribute/request';

// Verify the form
$name = $_POST['name'];
$onid = $_SESSION['onid'];
$email = $_SESSION['email'];
$seminar = $_POST['seminar'];
$role = htmlentities($_POST['role']);

if (!$name) fail($url, 'Please include your name');
if (!$onid || !$email) fail($url, 'ONID or email missing. Please log in.');
if (!$seminar) fail($url, 'Please enter the name of the seminar you led');
if (!$role) fail($url, 'Please provide details about your role in the seminar');

$from = 'From: IOTA Alliance <no-reply@iota.engr.orst.edu>';
$subject = '[IOTA] Contribution Request';

$toEmails = BASE . '/include/data/contact-us-emails.json';
$to = implode(',', json_decode(file_get_contents($toEmails), true));

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= $from . "\r\n";

$message = "
<html>
<head>
<title>Contribution Request</title>
</head>
<body>
<p>IOTA Administrator,</p>
<p>The following individual has requested permission to contribute to the resource repository.</p>
<p><strong>Name:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><Strong>ONID:</Strong> $onid</p>
<p><strong>Seminar:</strong> $seminar</p>
<p><strong>Role:</strong> $role</p>
</body>
</html>
";

try {
    mail($to, $subject, $message, $headers);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    fail($url, 'Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator');
}


header('Location: ' . BASE_URL . $url . '/?sent=true');
