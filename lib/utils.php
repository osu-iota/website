<?php

function fail($message, $redirect = 'error') {
    $_SESSION['message'] = array(
        'content' => $message,
        'type' => 'error'
    );
    echo "<script>location.replace('$redirect')</script>";
    die();
}

function urlContains($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}

function dump($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}
