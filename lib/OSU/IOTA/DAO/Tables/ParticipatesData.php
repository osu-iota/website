<?php
namespace OSU\IOTA\DAO\Tables;

class ParticipatesData extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'participates_data';
    const TABLE_ALIAS = 'pd';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'pdid';
    const DATA = 'pd_data';
    const EXT = 'pd_extension';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}