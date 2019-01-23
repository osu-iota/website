<?php

namespace OSU\IOTA\DAO;
use OSU\IOTA\Model\AllianceMember;
use OSU\IOTA\DAO\Tables\AllianceMember as AmTable;

class AllianceMemberDao {

    public static function convertToAllianceMember($row, $prefix = '') {
        $am = new AllianceMember($row[$prefix . AmTable::ID]);
        $am->setName($row[$prefix . AmTable::NAME]);
        $am->setDescription($row[$prefix . AmTable::DESCRIPTION]);
        $am->setUrl($row[$prefix . AmTable::URL]);
        $u = UserDao::convertToUser($row, $prefix . AmTable::HEAD . '_');
        $am->setHead($u);
        return $am;
    }
}