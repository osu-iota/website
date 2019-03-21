<?php

namespace DAO\Tables;

class ResourceData extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'resource_data';
    const TABLE_ALIAS = 'rd';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'rdid';
    const RESOURCE = 'rid';
    const DATA = 'rd_data';
    const EXT = 'rd_extension';
    const DATE = 'rd_date';
    const DOWNLOADS = 'rd_downloads';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}