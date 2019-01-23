<?php
namespace OSU\IOTA;

class SessionManager {

    private $user;

    public function __construct() {
        session_start();
        $this->user = $_SESSION[self::SKEY_USER];
    }

    public function terminate() {
        session_unset();
        session_destroy();
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $_SESSION[self::SKEY_USER] = $user;
        $this->user = $user;
    }



    const PREFIX = 'iota_';
    const SKEY_USER = self::PREFIX.'user';
}
