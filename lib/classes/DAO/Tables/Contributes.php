<?php
namespace DAO\Tables;

class Contributes extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'contributes';
    const TABLE_ALIAS = 'c';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const USER = 'uid';
    const RESOURCE = 'rid';
    const DATE = 'cn_date';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}