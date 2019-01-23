<?php
namespace OSU\IOTA\DAO;

class Dao {
    /** @var DbConnection */
    private $conn = null;

    public function __construct($dbconfig = null) {
        if($dbconfig != null) {
            $this->init($dbconfig);
        }
    }

    public function __destruct() {
        if($this->conn != null) {
            $this->conn->close();
        }
    }

    public function init($dbconfig) {
        $this->conn = new DbConnection($dbconfig['name'], $dbconfig['host'], $dbconfig['user'], $dbconfig['password']);
    }

    protected function getConnection() {
        return $this->conn;
    }
}