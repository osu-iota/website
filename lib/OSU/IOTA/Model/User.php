<?php

namespace osu\iota;

/**
 * @Entity @Table(name="iota_user")
 */
class User {

    /** @Id
     * @Column(name="uid", type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @Column(type="string") */
    private $onid;

    /** @Column(type="integer") */
    private $role;

    /** @Column(type="datetime") */
    private $lastLogin;

    /** @OneToMany(targetEntity="AttendedEvent", mappedBy="uid") */
    private $attendedEvents;

    /** @OneToMany(targetEntity="AllianceMember", mappedBy="head") */
    private $alliancesHeaded;

    private $registeredEvents;

    private $ledEvents;

    public function __construct($id = null) {
        $this->id = $id != null ? $id : uniqid();
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