<?php

namespace OSU\IOTA\DAO\Tables;

class Resource extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'resource';
    const TABLE_ALIAS = 'r';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'rid';
    const NAME = 'r_name';
    const DESCRIPTION = 'r_description';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}