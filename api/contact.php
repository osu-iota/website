<?php
ini_set('display_errors', 0);
include_once PUBLIC_FILES . '/lib/rest-utils.php';
include_once PUBLIC_FILES . '/lib/email.php';

// Define handlers
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        $body = readRequestBodyJson();

        // Verify the form
        $name = $body['name'];
        $email = $body['email'];
        $subject = $body['subject'];
        $content = htmlentities($body['content']);

        if (!$name) respond(400, 'Please include your name');
        if (!$email) respond(400, 'Please include your email');
        if (!$subject) respond(400, 'Please include your message subject');
        if (!$content) respond(400, 'Please include the content for your message');

        // Construct the message body
        $message = '<p>IOTA Administrator,</p>';
        $message .= $content;
        $message .= '<p>Sincerely,</p>';
        $message .= '<p>' . $name . '</p>';

        // Get list of emails to send the contact form to
        $to = $config['admin']['emails'];

        // Send and check for errors
        $error = sendEmail($subject, $message, $to, $name . ' <' . $email . '>');

        if ($error) {
            $logger->error('Error sending email: ' . $error);
            respond(500, 'Failed to send contact message');
        }

        respond(200, 'Message sent successfully');
        break;

    default:
        respond(400, 'Invalid request on contact resource');
}