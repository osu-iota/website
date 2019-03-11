<?php

namespace OSU\IOTA\DAO;

use OSU\IOTA\Model\User;
use OSU\IOTA\DAO\Tables\User as UserTable;

class UserDao extends Dao {

    /** @return User[] */
    public function getAllUsers() {
        $users = array();
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME;
        $result = $this->getConnection()->query($sql);
        foreach ($result as $row) {
            $users[] = self::extractUserFromRow($row);
        }
        return $users;
    }

    public function getUser($id) {
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME . ' WHERE ' . UserTable::ID . ' = :uid';
        $prepared = $this->getConnection()->prepare($sql);
        $prepared->bindParam(':uid', $id, \PDO::PARAM_STR);
        $prepared->execute();
        $result = $prepared->fetchAll();
        if (!$result) return null;
        return self::extractUserFromRow($result[0]);
    }

    public function getUserWithOnid($onid) {
        $sql = 'SELECT * FROM ' . UserTable::TABLE_NAME . ' WHERE ' . UserTable::ONID . ' = :onid';
        $prepared = $this->getConnection()->prepare($sql);
        $prepared->bindParam(':onid', $onid, \PDO::PARAM_STR);
        $prepared->execute();
        $result = $prepared->fetchAll();
        if (!$result) return null;
        return self::extractUserFromRow($result[0]);
    }

    /**
     * @param $user User
     * @return bool
     */
    public function createUser($user) {
        $sql = 'INSERT INTO ' . UserTable::TABLE_NAME . ' VALUES(:uid, :onid, :privilege, :lastLogin)';
        $prepared = $this->getConnection()->prepare($sql);
        $prepared->bindParam(':uid', $user->getId(), \PDO::PARAM_STR);
        $prepared->bindParam(':onid', $user->getOnid(), \PDO::PARAM_STR);
        $prepared->bindParam(':lastLogin', $user->getLastLogin(), \PDO::PARAM_INT);
        $prepared->bindParam(':privilege', $user->getPrivilegeLevel(), \PDO::PARAM_INT);
        try {
            $prepared->execute();
            return true;
        } catch(\Exception $e){
            return false;
        }
    }

    /**
     * @param $user User
     * @return bool
     */
    public function updateUser($user) {
        $sql = 'UPDATE ' . UserTable::TABLE_NAME . ' SET ';
        $sql .= UserTable::NAME . ' = :name, ';
        $sql .= UserTable::LAST_LOGIN . ' = :lastLogin,';
        $sql .= UserTable::PRIVILEGE_LEVEL . ' = :privilege ';
        $sql .= 'WHERE ' . UserTable::ID . ' = :uid';
        $prepared = $this->getConnection()->prepare($sql);
        $prepared->bindParam(':name', $user->getName(), \PDO::PARAM_STR);
        $prepared->bindParam(':lastLogin', $user->getLastLogin(), \PDO::PARAM_INT);
        $prepared->bindParam(':privilege', $user->getPrivilegeLevel(), \PDO::PARAM_INT);
        $prepared->bindParam(':uid', $user->getId(), \PDO::PARAM_STR);
        return $prepared->execute();
    }

    public static function extractUserFromRow($row) {
        $u = new User($row[UserTable::ID]);
        $u->setOnid($row[UserTable::ONID]);
        $u->setName($row[UserTable::NAME]);
        $u->setEmail($row[UserTable::ONID] . '@oregonstate.edu');
        $u->setPrivilegeLevel($row[UserTable::PRIVILEGE_LEVEL]);
        $u->setLastLogin($row[UserTable::LAST_LOGIN]);
        return $u;
    }
}

