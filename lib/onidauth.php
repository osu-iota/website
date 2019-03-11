<?php
session_start();

function onidauth() {

    if (isset($_SESSION["onid"])) return;

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

        $_SESSION['onid'] = strtolower(extractFromXml('cas:user', $html));

        $name = extractFromXml('cas:firstname', $html) . ' ' . extractFromXml('cas:lastname', $html);

        // Check to see if the user already exists in the database. If they don't, create a new entry
        global $daoUsers;
        $user = $daoUsers->getUserWithOnid($_SESSION['onid']);
        if(!$user) {
            $user = new OSU\IOTA\Model\User();
            $user->setOnid($_SESSION['onid']);
            $user->setPrivilegeLevel(0);
            $user->setName($name);
            $daoUsers->createUser($user);
            // TODO: add failure check here
        } else {
            // Update their last login and name
            $user->setLastLogin(time());
            $user->setName($name);
            $daoUsers->updateUser($user);
            // TODO: add failure check here
        }

        echo "<script>location.replace('" . $pageURL . "');</script>";

    } else {
        $url = "https://login.oregonstate.edu/cas/login?service=" . $pageURL;
        echo "<script>location.replace('" . $url . "');</script>";
    }
}

function extractFromXml($key, $xml) {
    $pattern = '/\\<' . $key . '\\>([a-zA-Z0-9@\.]+)\\<\\/' . $key . '\\>/';
    preg_match($pattern, $xml, $matches);
    if ($matches && count($matches) > 1) {
        return $matches[1];
    }
    return false;
}