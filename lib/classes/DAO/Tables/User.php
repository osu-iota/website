<?php

namespace DAO\Tables;

class User {
    const TABLE_NAME = IotaTable::DB_PREFIX . 'user';
    const TABLE_ALIAS = 'u';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'uid';
    const NAME = 'u_name';
    const ONID = 'u_onid';
    const PRIVILEGE_LEVEL = 'u_privilege_level';
    const LAST_LOGIN = 'u_last_login';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}