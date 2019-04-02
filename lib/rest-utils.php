<?php
function respond($status, $message, $data = null) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code($status);
    $body = array(
        'code' => $status,
        'success' => $status > 199 && $status < 300,
        'message' => $message,
        'content' => $data
    );
    echo json_encode($body);
    exit(0);
}

function readRequestBodyJson() {
    return json_decode(file_get_contents('php://input'), true);
}

function readRequestBodyUrlFormEncoded() {
    $encodedBody = $_POST;
    foreach ($_FILES as $name => $file) {
        $encodedBody[$name] = $file;
    }

    return $encodedBody;
}

function readQueryString() {
    $query = array();
    parse_str($_SERVER['QUERY_STRING'], $query);

    return $query;
}
