<?php

namespace osu\iota;

/**
 * @Entity @Table(name="iota_event")
 */
class Event {

    /** @Id
     * @Column(name="eid", type="integer")
     * @GeneratedValue
     * @OneToMany(targetEntity="AttendedEvent", mappedBy="eid")
     */
    private $id;

    /** @Column(type="string") */
    private $title;

    /** @Column(type="text") */
    private $description;

    /** @Column(type="datetime") */
    private $date;

    /** @Column(type="string") */
    private $location;

    /** @var @Column(type="text") */
    private $details;

    /** @OneToOne(targetEntity="AllianceMember")
     * @JoinColumn(referencedColumnName="aid")
     */
    private $sponsor;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details) {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getSponsor() {
        return $this->sponsor;
    }

    /**
     * @param mixed $sponsor
     */
    public function setSponsor($sponsor) {
        $this->sponsor = $sponsor;
    }



}