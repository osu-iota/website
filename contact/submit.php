<?php
session_start();

$url = 'contact/';

// Verify the form
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$content = $_POST['content'];

if (!$name) fail($url, 'Please include your name');
if (!$email) fail($url, 'Please include your email');
if (!$subject) fail($url, 'Please include a subject for your message');
if (!$content) fail($url, 'Please provide details about your inquiry');

$from = 'From: ' . $name . '<' . $email . '>';
$subject = '[IOTA Contact Us] ' . $subject;

$toEmails = BASE . '/include/data/contact-us-emails.json';
$to = implode(',', json_decode(file_get_contents($toEmails), true));

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= $from . "\r\n";

$message = "
<html>
<head>
<title>$subject</title>
</head>
<body>
<p>IOTA Administrator,</p>
<p>$content</p>
<p>Sincerly,</p>
<p>$name</p>
</body>
</html>
";

try {
    mail($to, $subject, $message, $headers);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    fail($url, 'Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator');
}


header('Location: ' . BASE_URL . '/contact/?sent=true');
