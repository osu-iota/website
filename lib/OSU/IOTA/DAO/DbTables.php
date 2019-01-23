<?php
namespace OSU\IOTA\Tables;

const DB_PREFIX = 'iota_';

class Users {
    const TABLE_NAME = DB_PREFIX.'users';
    const UID = 'uid';
    const NAME = 'name';
    const ONID = 'onid';
    const ROLE = 'role';
    const LAST_LOGIN = 'last_login';
    const TABLE_INSERT = 'INSERT INTO '.self::TABLE_NAME.
    ' ('.self::UID.','.self::NAME.','.self::ONID.','. self::ROLE.','.self::LAST_LOGIN.') VALUES(?,?,?,?,?)';
}

class Events {
    const TABLE_NAME = DB_PREFIX.'events';
}

class Attends {
    const TABLE_NAME = DB_PREFIX.'attends';
}

class RegistersFor {
    const TABLE_NAME = DB_PREFIX.'registers_for';
}

class LedBy {
    const TABLE_NAME = DB_PREFIX.'led_by';
}