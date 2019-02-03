<?php

namespace OSU\IOTA\DAO\Tables;

class ResourceFor extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'resource_for';
    const TABLE_ALIAS = 'rf';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const RESOURCE = 'rid';
    const TOPIC = 'rtid';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}