<?php

namespace DAO\Tables;

class ResourceTopic extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'resource_topic';
    const TABLE_ALIAS = 'rt';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'rtid';
    const NAME = 'rt_name';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}