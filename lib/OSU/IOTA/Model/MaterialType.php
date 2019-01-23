<?php

namespace osu\iota;

class MaterialType {
    protected $id;
    protected $name;

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


}