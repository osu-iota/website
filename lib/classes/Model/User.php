<?php

namespace Model;
use Security;

class User {

    private $id;
    private $onid;
    private $lastLogin;
    private $privilegeLevel;
    private $name;
    private $email;

    public function __construct($id = null) {
        $this->id = $id != null ? $id : Security::generateSecureUniqueId();
        $this->lastLogin = time();
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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

}