<?php
session_start();
if(!$userIsManager) {
    $_SESSION['message'] = array(
        'content' => 'You do not have permission to access this portion of the site',
        'type' => 'error'
    );
    header('Location: ' . BASE_URL . 'error/');
    die();
}