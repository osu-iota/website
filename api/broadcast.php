<?php
ini_set('display_errors', 0);
// Restrict page access to admins
include_once PUBLIC_FILES . '/lib/authorize.php';
allowIf($userIsAdmin);

include_once PUBLIC_FILES . '/lib/rest-utils.php';
include_once PUBLIC_FILES . '/lib/email.php';

// Define handlers
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        $body = readRequestBodyJson();

        $subject = htmlentities($body['subject']);
        $content = htmlentities($body['content']);

        // Verify the form
        if ($subject . '' === '') respond(400, 'Please include a message subject');
        if ($content . '' === '') respond(400, 'Please include message content');

        // Get the users from the database
        $users = $daoUsers->getAllUsers();
        $failed = array();
        foreach ($users as $user) {
            $name = $user->getName();
            $to = $user->getEmail() . ' <' . $name . '>';

            $message = "<p>$name,</p>";
            $message .= "<p>$content</p>";
            $message .= "<p>Sincerely,</p><p>Oregon State University IoT Alliance</p>";

            // Send and check for errors
            $error = sendEmail($subject, $message, $to);

            if ($error) {
                $logger->error("Failed to send email to $to: $error");
                $failed[] = $to;
            }
        }

        if (count($failed) != 0) {
            respond(500, 'Failed to send all emails. The following emails were not sent.', array('failed' => $failed));
        }

        respond(200, 'Messages sent successfully to all users');
        break;


    default:
        respond(400, 'Invalid request on user broadcast message resource');
}