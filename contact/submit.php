<?php

$from = array(
    "name" => "IoT Alliace",
    "email" => "webmaster@engr.oregonstate.edu"
);

$recipients = array(
    array(
        "name" => "Braden Hitchcock",
        "email" => "hitchcob@oregonstate.edu"
    ),
    array(
        "name" => "Chris Scaffidi",
        "email" => "scaffidc@oregonstate.edu"
    )
);

header('Location: ' . BASE_URL . '/contact/?sent=true');
