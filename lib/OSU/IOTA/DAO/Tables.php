<?php

namespace OSU\IOTA\DAO\Tables;

const DB_PREFIX = 'iota_';

class User {
    const TABLE_NAME = DB_PREFIX . 'user';
    const TABLE_ALIAS = 'u';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'uid';
    const NAME = 'u_name';
    const ONID = 'u_onid';
    const ROLE = 'u_role';
    const LAST_LOGIN = 'u_last_login';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Event {
    const TABLE_NAME = DB_PREFIX . 'event';
    const TABLE_ALIAS = 'e';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'eid';
    const TITLE = 'e_title';
    const DESCRIPTION = 'e_description';
    const DATE = 'e_date';
    const LOCATION = 'e_location';
    const SPONSOR = 'e_sponsor';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class AllianceMember {
    const TABLE_NAME = DB_PREFIX . 'alliance_member';
    const TABLE_ALIAS = 'am';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'aid';
    const NAME = 'am_name';
    const DESCRIPTION = 'am_description';
    const URL = 'am_url';
    const HEAD = 'am_head';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Material {
    const TABLE_NAME = DB_PREFIX . 'material';
    const TABLE_ALIAS = 'm';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'mid';
    const NAME = 'm_name';
    const DESCRIPTION = 'm_description';
    const TYPE = 'm_type';
    const FILE = 'm_file';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class MaterialType {
    const TABLE_NAME = DB_PREFIX . 'material_type';
    const TABLE_ALIAS = 'mt';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const ID = 'mtid';
    const NAME = 'mt_name';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Attends {
    const TABLE_NAME = DB_PREFIX . 'attends';
    const TABLE_ALIAS = 'a';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const USER_ID = 'uid';
    const EVENT_ID = 'eid';
    const SELFIE = 'a_selfie';
    const RATING = 'a_rating';
    const COMMENTS = 'a_comments';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class RegistersFor {
    const TABLE_NAME = DB_PREFIX . 'registers_for';
    const TABLE_ALIAS = 'rf';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const USER_ID = 'uid';
    const EVENT_ID = 'eid';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class LedBy {
    const TABLE_NAME = DB_PREFIX . 'led_by';
    const TABLE_ALIAS = 'lb';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const EVENT_ID = 'eid';
    const USER_ID = 'uid';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Contributes {
    const TABLE_NAME = DB_PREFIX . 'contributes';
    const TABLE_ALIAS = 'c';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const USER_ID = 'uid';
    const MATERIAL_ID = 'mid';
    const DATE = 'c_date';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class ResourceFor {
    const TABLE_NAME = DB_PREFIX . 'resource_for';
    const TABLE_ALIAS = 'rsf';
    const TABLE_NAME_ALIAS = self::TABLE_NAME . ' ' . self::TABLE_ALIAS;
    const MATERIAL_ID = 'mid';
    const EVENT_ID = 'eid';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }

}