<?php

namespace OSU\IOTA\DAO;

use OSU\IOTA\Model\User;
use OSU\IOTA\DAO\Tables\User as UserTable;

class UserDao extends Dao {

    public function getAllUsers() {
        $users = array();
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME;
        $result = $this->getConnection()->query($sql);
        foreach ($result as $row) {
            $users[] = $this->convertToUser($row);
        }
        return $users;
    }

    public function getUser($id) {
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME . ' WHERE ' . UserTable::ID . ' = ?';
        $result = $this->getConnection()->query($sql, [$id]);
        return $this->convertToUser($result[0]);
    }

    public function getUserWithOnid($onid) {
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME . ' WHERE ' . UserTable::ONID . ' = ?';
        $result = $this->getConnection()->query($sql, [$onid]);
        return $this->convertToUser($result[0]);
    }

    /**
     * @param $user User
     * @return bool
     */
    public function createUser($user) {
        $values = array(
            $user->getId(),
            $user->getOnid(),
            $user->getRole(),
            $user->getLastLogin()
        );
        $sql = 'INSERT INTO ' . UserTable::TABLE_NAME .
            ' (' . UserTable::ID . ',' . UserTable::NAME . ',' . UserTable::ONID . ',' . UserTable::ROLE . ',' . UserTable::LAST_LOGIN . ') VALUES(?,?,?,?,?)';
        return $this->getConnection()->exec($sql, $values);
    }

    /**
     * @param $user User
     * @return bool
     */
    public function updateUser($user) {
        $sql = 'UPDATE ' . UserTable::TABLE_NAME . ' SET ';
        $sql .= UserTable::NAME . ' = ?,';
        $sql .= UserTable::LAST_LOGIN . ' = ?,';
        $sql .= UserTable::ROLE . ' = ? ';
        $sql .= 'WHERE ' . UserTable::ID . ' = ?';
        $values = array(
            $user->getName(),
            $user->getLastLogin(),
            $user->getRole(),
            $user->getId()
        );
        return $this->getConnection()->exec($sql, $values);
    }

    public static function convertToUser($row, $prefix = '') {
        $u = new User($row[$prefix . UserTable::ID]);
        $u->setName($row[$prefix . UserTable::NAME]);
        $u->setOnid($row[$prefix . UserTable::ONID]);
        $u->setRole($row[$prefix . UserTable::ROLE]);
        $u->setLastLogin($row[$prefix . UserTable::LAST_LOGIN]);
        return $u;
    }
}

