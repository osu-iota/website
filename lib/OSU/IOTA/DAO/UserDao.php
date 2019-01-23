<?php
namespace OSU\IOTA;

class UserDao extends Dao {

    public function getAll() {
        $users = array();
        $sql = 'SELECT * FROM '.Tables\Users::TABLE_NAME;
        $result = $this->getConnection()->query($sql);
        foreach($result as $row) {
            $users[] = $this->createUserFromRow($row);
        }
        return $users;
    }

    public function get($id) {
        $sql = 'SELECT * FROM '.Tables\Users::TABLE_NAME.' WHERE '.Tables\Users::UID.' = ?';
        $result = $this->getConnection()->query($sql,[$id]);
        return $this->createUserFromRow($result[0]);
    }

    public function getWithOnid($onid) {
        $sql = 'SELECT * FROM '.Tables\Users::TABLE_NAME.' WHERE '.Tables\Users::ONID.' = ?';
        $result = $this->getConnection()->query($sql, [$onid]);
        return $this->createUserFromRow($result[0]);
    }

    /**
     * @param $user User
     * @return bool
     */
    public function create($user) {
        $values = array(
            $user->getId(),
            $user->getOnid(),
            $user->getRole(),
            $user->getLastLogin()
        );
        return $this->getConnection()->exec(Tables\Users::TABLE_INSERT, $values);
    }

    private function createUserFromRow($row) {
        $u = new User($row[Tables\Users::UID]);
        $u->setName($row[Tables\Users::NAME]);
        $u->setOnid($row[Tables\Users::ONID]);
        $u->setRole($row[Tables\Users::ROLE]);
        $u->setLastLogin($row[Tables\Users::LAST_LOGIN]);
        return $u;
    }
}

