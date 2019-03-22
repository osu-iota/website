<?php

include_once PUBLIC_FILES . '/lib/rest-utils.php';

function allowIf($condition, $api = true) {
    if(!$condition) {
        if($api) {
            respond(401, 'You do not have access to the requested resource or action');
        } else {
            fail('You do not have access to the requested page');
        }
    }
}