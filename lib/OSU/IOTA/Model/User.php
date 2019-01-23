<?php

namespace OSU\IOTA\Model;
use OSU\IOTA\Util\Security;

class User {

    private $id;

    private $name;

    private $onid;

    private $role;

    private $lastLogin;

    private $attendedEvents;

    private $alliancesHeaded;

    private $registeredEvents;

    private $ledEvents;

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

    /**
     * @return mixed
     */
    public function getAttendedEvents() {
        return $this->attendedEvents;
    }

    /**
     * @param mixed $attendedEvents
     */
    public function setAttendedEvents($attendedEvents) {
        $this->attendedEvents = $attendedEvents;
    }

    /**
     * @return mixed
     */
    public function getAlliancesHeaded() {
        return $this->alliancesHeaded;
    }

    /**
     * @param mixed $alliancesHeaded
     */
    public function setAlliancesHeaded($alliancesHeaded) {
        $this->alliancesHeaded = $alliancesHeaded;
    }

    /**
     * @return mixed
     */
    public function getRegisteredEvents() {
        return $this->registeredEvents;
    }

    /**
     * @param mixed $registeredEvents
     */
    public function setRegisteredEvents($registeredEvents) {
        $this->registeredEvents = $registeredEvents;
    }

    /**
     * @return mixed
     */
    public function getLedEvents() {
        return $this->ledEvents;
    }

    /**
     * @param mixed $ledEvents
     */
    public function setLedEvents($ledEvents) {
        $this->ledEvents = $ledEvents;
    }


}