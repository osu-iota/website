<?php
include_once '../../authorize.php';


$url = 'admin/users/email';

$subject = htmlentities($_POST['subject']);
$content = htmlentities($_POST['content']);

// Verify the form
if ($subject . '' === '') fail('Please include a message subject', $url);
if ($content . '' === '') fail('Please include message content', $url);

// Get the users from the database
//$users = $daoUsers->getAllUsers();
$users = array($user);

// Prep Headers
$from = 'From: IOTA Alliance <no-reply@iota.engr.orst.edu>';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= $from . "\r\n";

foreach ($users as $user) {
    // Construct their email
    $to = $user->getOnid() . '@oregonstate.edu';
    $name = $user->getName();

    $message = "
    <html>
    <head>
    <title>IOTA Notification</title>
    <style> p { white-space: pre-wrap; } </style>
    </head>
    <body>
    <p>$name,</p>
    <p>$content</p>
    <p>Sincerely,</p>
    <p>Oregon State University IOTA Alliance</p>
    </body>
    </html>
    ";

    try {
        mail($to, $subject, $message, $headers);
    } catch (Exception $e) {
        $logger->error($e->getMessage());
        fail('Failed to send message. Please try again. If the problem persists, contact the IT department or a system administrator', $url);
    }

}

echo "<script>location.replace('" . BASE_URL . $url . "/?sent=true');</script>";