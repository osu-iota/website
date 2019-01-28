<?php

namespace OSU\IOTA\DAO;

use OSU\IOTA\Model\AllianceMember;
use OSU\IOTA\DAO\Tables\AllianceMember as AmTable;
use OSU\IOTA\DAO\Tables\User as UserTable;

class AllianceMemberDao extends Dao {

    public function getAllAllianceMembers() {
        $members = array();
        $sql = $this->generateJoinedSelect();
        $results = $this->getConnection()->query($sql);
        foreach ($results as $row) {
            $members[] = self::extractAllianceMemberFromRow($row);
        }
        return $members;
    }

    public function getAllianceMember($id) {
        $sql = $this->generateJoinedSelect();
        $sql .= ' AND ' . AmTable::aliased(AmTable::ID) . ' = ?';
        $result = $this->getConnection()->query($sql, [$id]);
        return self::extractAllianceMemberFromRow($result[0]);
    }

    /**
     * @param $member AllianceMember
     * @return bool
     */
    public function createAllianceMember($member) {
        $sql = 'INSERT INTO ' . AmTable::TABLE_NAME . ' VALUES (?,?,?,?,?)';
        $values = array(
            $member->getId(),
            $member->getName(),
            $member->getDescription(),
            $member->getUrl(),
            $member->getHead()->getId()
        );
        return $this->getConnection()->exec($sql, $values);
    }

    /**
     * @param $member AllianceMember
     * @return bool
     */
    public function updateAllianceMember($member) {
        $sql = 'UPDATE  ' . AmTable::TABLE_NAME . ' SET ';
        $sql .= AmTable::NAME . ' = ?, ';
        $sql .= AmTable::DESCRIPTION . ' = ?, ';
        $sql .= AmTable::URL . ' = ?, ';
        $sql .= AmTable::HEAD . ' = ? ';
        $sql .= 'WHERE ' . AmTable::ID . ' = ?';
        $params = array(
            $member->getName(),
            $member->getDescription(),
            $member->getUrl(),
            $member->getHead()->getId(),
            $member->getId()
        );
        return $this->getConnection()->exec($sql, $params);
    }

    private function generateJoinedSelect() {
        $sql = 'SELECT * ';
        $sql .= 'FROM ' . \implode(', ', [UserTable::TABLE_NAME_ALIAS, AmTable::TABLE_NAME_ALIAS]) . ' ';
        $sql .= 'WHERE ' . AmTable::aliased(AmTable::HEAD) . ' = ' . UserTable::ID;
        return $sql;
    }

    public static function extractAllianceMemberFromRow($row) {
        $am = new AllianceMember($row[AmTable::ID]);
        $am->setName($row[AmTable::NAME]);
        $am->setDescription($row[AmTable::DESCRIPTION]);
        $am->setUrl($row[AmTable::URL]);
        $u = UserDao::extractUserFromRow($row);
        $am->setHead($u);
        return $am;
    }

}