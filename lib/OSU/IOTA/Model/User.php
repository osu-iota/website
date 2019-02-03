<?php

namespace OSU\IOTA\Model;
use OSU\IOTA\Util\Security;

class User {

    private $id;
    private $onid;
    private $lastLogin;
    private $privilegeLevel;

    public function __construct($id = null) {
        $this->id = $id != null ? $id : Security::generateSecureUniqueId();
    }

    /**
     * @return string|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOnid() {
        return $this->onid;
    }

    /**
     * @param mixed $onid
     */
    public function setOnid($onid) {
        $this->onid = $onid;
    }

    /**
     * @return mixed
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     */
    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return mixed
     */
    public function getPrivilegeLevel() {
        return $this->privilegeLevel;
    }

    /**
     * @param mixed $privilegeLevel
     */
    public function setPrivilegeLevel($privilegeLevel) {
        $this->privilegeLevel = $privilegeLevel;
    }

}