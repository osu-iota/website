<?php

function sendEmail($subject, $message, $to, $from) {
    $subject = '[IOTA] ' . $subject;
    if (is_array($to)) $to = implode(',', $to);

    $from = 'From: ' . $from;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= $from . "\r\n";

    $html = '<html><head><title>' . $subject . '</title><style> p { white-space: pre-wrap; } </style></head>';
    $html .= '<body>' . $message . '</body></html>';

    try {
        mail($to, $subject, $html, $headers);
    } catch (Exception $e) {
        return $e->getMessage();
    }

    return null;
}