<?php
namespace OSU\IOTA\DAO\Tables;

class Contributes extends IotaTable {
    public const TABLE_NAME = self::DB_PREFIX . 'contributes';
    public const TABLE_ALIAS = 'c';
    public const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    public const USER = 'uid';
    public const RESOURCE = 'rid';
    public const DATE = 'cn_date';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}