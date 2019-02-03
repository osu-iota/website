<?php
namespace OSU\IOTA\DAO;

class Dao {
    /** @var \PDO */
    private $conn = null;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function __destruct() {
        if($this->conn != null){
            // Destroy the reference to the PDO object so that it can be closed later
            $this->conn = null;
        }
    }

    protected function getConnection() {
        return $this->conn;
    }
}