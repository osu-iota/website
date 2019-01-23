<?php

namespace osu\iota;

/**
 * @Entity @Table(name="iota_alliance_member")
 */
class AllianceMember {

    /** @Id
     * @Column(name="aid", type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @Column(type="text") */
    private $description;

    /** @Column(type="string") */
    private $url;

    /** @ManyToOne(targetEntity="User", inversedBy="uid") */
    private $head;

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
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHead() {
        return $this->head;
    }

    /**
     * @param mixed $head
     */
    public function setHead($head) {
        $this->head = $head;
    }


}