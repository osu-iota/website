<?php

namespace OSU\IOTA\DAO\Tables;

const DB_PREFIX = 'iota_';

class User {
    const TABLE_NAME = DB_PREFIX . 'user';
    const TABLE_ALIAS = 'u';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'uid';
    const ONID = 'u_onid';
    const PRIVILEGE_LEVEL = 'u_privilege_level';
    const LAST_LOGIN = 'u_last_login';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Resource {
    const TABLE_NAME = DB_PREFIX . 'resource';
    const TABLE_ALIAS = 'r';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'rid';
    const NAME = 'r_name';
    const DESCRIPTION = 'r_description';
    const TOPIC = 'r_topic';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class ResourceData {
    const TABLE_NAME = DB_PREFIX . 'resource_data';
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

class Participates {
    const TABLE_NAME = DB_PREFIX . 'participates';
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

class ParticipateData {
    const TABLE_NAME = DB_PREFIX . 'participate_data';
    const TABLE_ALIAS = 'pd';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'pdid';
    const DATA = 'pd_data';
    const EXT = 'pd_extension';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Contributes {
    const TABLE_NAME = DB_PREFIX . 'contributes';
    const TABLE_ALIAS = 'c';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const USER = 'uid';
    const RESOURCE = 'rid';
    const DATE = 'cn_date';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}