<?php
namespace DAO\Tables;

class Participates extends IotaTable {
    const TABLE_NAME = self::DB_PREFIX . 'participates';
    const TABLE_ALIAS = 'p';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'pid';
    const USER = 'uid';
    const TYPE = 'p_type';
    const CLUB = 'p_club';
    const DESCRIPTION = 'p_description';
    const DATA = 'p_data';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}