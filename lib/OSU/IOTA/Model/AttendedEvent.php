<?php
namespace osu\iota;

/**
 * @Entity @Table(name="iota_attends")
 */
class AttendedEvent {
    /** @Id
     * @Column(name="uid", type="integer")
     * @ManyToOne(targetEntity="User", inversedBy="uid")
     */
    private $user;

    /** @Id
     * @Column(name="eid", type="integer")
     * @ManyToOne(targetEntity="Event", inversedBy="eid")
     */
    private $event;

    /** @Column(type="string") */
    private $selfie;

    /** @Column(type="decimal") */
    private $rating;

    /** @Column(type="text") */
    private $comments;

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event) {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getSelfie() {
        return $this->selfie;
    }

    /**
     * @param mixed $selfie
     */
    public function setSelfie($selfie) {
        $this->selfie = $selfie;
    }

    /**
     * @return mixed
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments) {
        $this->comments = $comments;
    }




}