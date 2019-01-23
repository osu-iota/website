<?php

namespace OSU\IOTA\Model;
use OSU\IOTA\Util\Security;

class User {

    private $id;
    private $name;
    private $onid;
    private $role;
    private $lastLogin;

    public function __construct($id = null) {
        $this->id = $id != null ? $id : Security::generateSecureUniqueId();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
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
    public function getRole() {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role) {
        $this->role = $role;
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

}