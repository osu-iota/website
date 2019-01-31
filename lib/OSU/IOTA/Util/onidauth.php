<?php
session_start();
function onidauth($omitRedirect) {

    if (isset($_SESSION["onid"])) return $_SESSION["onid"];

    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["SCRIPT_NAME"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"];
    }

    $ticket = $_REQUEST["ticket"];

    if ($ticket . "" != "") {
        $url = "https://login.oregonstate.edu/cas/serviceValidate?ticket=" . $ticket . "&service=" . $pageURL;
        $html = file_get_contents($url);

        $_SESSION['onid'] = extractFromXml('cas:user', $html);
        $_SESSION['fname'] = extractFromXml('cas:firstname', $html);
        $_SESSION['lname'] = extractFromXml('cas:lastname', $html);
        $_SESSION['email'] = extractFromXml('cas:email', $html);

        echo "<script>location.replace('" . $pageURL . "');</script>";

    } else if (!$omitRedirect) {
        $url = "https://login.oregonstate.edu/cas/login?service=" . $pageURL;
        echo "<script>location.replace('" . $url . "');</script>";
    }

    return "";

}

function extractFromXml($key, $xml) {
    $pattern = '/\\<' . $key . '\\>([a-zA-Z0-9@\.]+)\\<\\/' . $key . '\\>/';
    preg_match($pattern, $xml, $matches);
    if ($matches && count($matches) > 1) {
        return $matches[1];
    }
    return false;
}