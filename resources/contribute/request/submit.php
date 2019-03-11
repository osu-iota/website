<?php
session_start();

$url = 'resources/contribute/request';

// Verify the form
$name = $_POST['name'];
$onid = $user ? $user->getOnid() : null;
$email = $user ? $user->getEmail() : null;
$seminar = $_POST['seminar'];
$role = htmlentities($_POST['role']);

if (!$name) fail('Please include your name', $url);
if (!$onid || !$email) fail('ONID or email missing. Please log in.', $url);
if (!$seminar) fail('Please enter the name of the seminar you led', $url);
if (!$role) fail('Please provide details about your role in the seminar', $url);

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
    fail('Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator', $url);
}


header('Location: ' . BASE_URL . $url . '/?sent=true');
