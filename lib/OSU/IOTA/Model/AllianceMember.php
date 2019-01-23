<?php

namespace OSU\IOTA\Model;

use OSU\IOTA\Util\Security;

class AllianceMember {

    private $id;
    private $name;
    private $description;
    private $url;
    private $head;

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