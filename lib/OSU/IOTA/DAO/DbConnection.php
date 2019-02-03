<?php

namespace OSU\IOTA\DAO;

class DbConnection {

    /** @var $conn \PDO */
    private $conn;

    public function __construct($dbname, $host, $user, $password) {
        $url = 'mysql:host=' . $host . ';dbname=' . $dbname;
        $this->conn = new \PDO($url, $user, $password);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql, $params = []) {
        if ($this->conn == null) {
            return null;
        }
        try {
            $statement = $this->conn->prepare($sql);
            for ($x = 0; $x < count($params); $x++) {
                $statement->bindParam($x + 1, $params[$x]);
            }
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function exec($sql, $params = []) {
        if ($this->conn == null) {
            return false;
        }
        try {
            $statement = $this->conn->prepare($sql);
            for ($x = 0; $x < count($params); $x++) {
                $statement->bindParam($x + 1, $params[$x]);
            }
            $statement->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }

    }

    public function execTransaction($statements) {
        if ($this->conn != null) {
            try {
                $this->conn->beginTransaction();
                foreach ($statements as $s) {
                    $this->exec($s[0], $s[1]);
                }
                $this->conn->commit();
                return true;
            } catch (\PDOException $e) {
                $this->conn->rollBack();
                return false;
            }
        }
    }

    public function close() {
        $this->conn = null;
    }
}