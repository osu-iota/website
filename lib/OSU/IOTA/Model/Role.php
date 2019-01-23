<?php

namespace osu\iota;

class Role {
    protected $id;
    protected $name;
    protected $description;

    function __construct($id = null) {
        $this->id = $id;
    }

    /**
     * @return null
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


}