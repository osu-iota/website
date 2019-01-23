<?php

namespace OSU\IOTA\DAO\Tables;

const DB_PREFIX = 'iota_';

class User {
    const TABLE_NAME = DB_PREFIX . 'user';
    const TABLE_ALIAS = 'u';
    const ID = 'uid';
    const NAME = 'name';
    const ONID = 'onid';
    const ROLE = 'role';
    const LAST_LOGIN = 'last_login';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Event {
    const TABLE_NAME = DB_PREFIX . 'event';
    const TABLE_ALIAS = 'e';
    const ID = 'eid';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const DATE = 'date';
    const LOCATION = 'location';
    const SPONSOR = 'sponsor';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class AllianceMember {
    const TABLE_NAME = DB_PREFIX . 'alliance_member';
    const TABLE_ALIAS = 'am';
    const ID = 'aid';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const URL = 'url';
    const HEAD = 'head';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::ID . ',' . self::NAME . ',' . self::DESCRIPTION . ',' . self::URL . ',' . self::HEAD . ') VALUES(?,?,?,?,?)';

    public static function aliased($col) {
        return self::TABLE_ALIAS . '.' . $col;
    }
}

class Material {
    const TABLE_NAME = DB_PREFIX . 'material';
    const TABLE_ALIAS = 'm';
    const ID = 'mid';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const TYPE = 'type';
    const FILE = 'file';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::ID . ',' . self::NAME . ',' . self::DESCRIPTION . ',' . self::TYPE . ',' . self::FILE . ') VALUES(?,?,?,?,?)';
}

class MaterialType {
    const TABLE_NAME = DB_PREFIX . 'material_type';
    const TABLE_ALIAS = 'mt';
    const ID = 'mtid';
    const NAME = 'name';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::ID . ',' . self::NAME . ') VALUES(?,?)';
}

class Attends {
    const TABLE_NAME = DB_PREFIX . 'attends';
    const TABLE_ALIAS = 'a';
    const USER_ID = 'uid';
    const EVENT_ID = 'eid';
    const SELFIE = 'selfie';
    const RATING = 'rating';
    const COMMENTS = 'comments';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::USER_ID . ',' . self::EVENT_ID . ',' . self::SELFIE . ',' . self::RATING . ',' . self::COMMENTS . ') VALUES(?,?,?,?,?)';
}

class RegistersFor {
    const TABLE_NAME = DB_PREFIX . 'registers_for';
    const TABLE_ALIAS = 'rf';
    const USER_ID = 'uid';
    const EVENT_ID = 'eid';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::USER_ID . ',' . self::EVENT_ID . ') VALUES(?,?)';
}

class LedBy {
    const TABLE_NAME = DB_PREFIX . 'led_by';
    const TABLE_ALIAS = 'lb';
    const EVENT_ID = 'eid';
    const USER_ID = 'uid';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::EVENT_ID . ',' . self::USER_ID . ') VALUES(?,?)';
}

class Contributes {
    const TABLE_NAME = DB_PREFIX . 'contributes';
    const TABLE_ALIAS = 'c';
    const USER_ID = 'uid';
    const MATERIAL_ID = 'mid';
    const DATE = 'date';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::USER_ID . ',' . self::MATERIAL_ID . ',' . self::DATE . ') VALUES(?,?,?)';
}

class ResourceFor {
    const TABLE_NAME = DB_PREFIX . 'resource_for';
    const TABLE_ALIAS = 'rsf';
    const MATERIAL_ID = 'mid';
    const EVENT_ID = 'eid';
    const TABLE_INSERT = 'INSERT INTO ' . self::TABLE_NAME .
    ' (,' . self::MATERIAL_ID . ',' . self::EVENT_ID . ') VALUES(?,?)';

}