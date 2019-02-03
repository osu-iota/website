<?php
session_start();
/**
 * @param $db OSU\IOTA\DAO\DbConnection
 * @return string
 */
function onidauth($db) {

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
        $user = getIotaUserInfo($db);
        $_SESSION['privilegeLevel'] = $user->getPrivilegeLevel();
        $_SESSION['uid'] = $user->getId();

        echo "<script>location.replace('" . $pageURL . "');</script>";

    } else {
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

/**
 * @param $db OSU\IOTA\DAO\DbConnection
 * @return \OSU\IOTA\Model\User
 */
function getIotaUserInfo($db){
    // If the user doesn't exist in our database, add them. Otherwise get their IOTA uid and privilege level
    $dao = new OSU\IOTA\DAO\UserDao($db);
    $user = $dao->getUserWithOnid($_SESSION['onid']);
    if(!$user) {
        $user = new OSU\IOTA\Model\User();
        $user->setOnid($_SESSION['onid']);
        $user->setPrivilegeLevel(0);
        $user->setLastLogin(time());
        // TODO: add failure check here
        $dao->createUser($user);
    }
    return $user;
}