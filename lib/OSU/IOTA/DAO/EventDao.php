<?php

namespace OSU\IOTA\DAO;

use OSU\IOTA\DAO\Tables\Event as EventTable;
use OSU\IOTA\DAO\Tables\AllianceMember as AmTable;
use OSU\IOTA\DAO\Tables\User as UserTable;
use OSU\IOTA\Model\Event;

class EventDao extends Dao {

    public function getAllEvents() {
        $events = array();
        $sql = $this->generateJoinedSelect();
        $result = $this->getConnection()->query($sql);
        foreach ($result as $row) {
            $events[] = $this->convertToEvent($row);
        }
        return $events;
    }

    public function getEvent($id) {
        $sql = $this->generateJoinedSelect();
        $sql .= ' WHERE ' . EventTable::aliased(EventTable::ID) . ' = ?';
        $result = $this->getConnection()->query($sql, [$id]);
        return $this->convertToEvent($result[0]);
    }

    /**
     * @param $event Event
     * @return bool
     */
    public function createEvent($event) {
        $sql = 'INSERT INTO ' . EventTable::TABLE_NAME . '(';
        $sql .= EventTable::ID . ',';
        $sql .= EventTable::TITLE . ',';
        $sql .= EventTable::DESCRIPTION . ',';
        $sql .= EventTable::DATE . ',';
        $sql .= EventTable::LOCATION . ',';
        $sql .= EventTable::SPONSOR . ') ';
        $sql .= 'VALUES(?,?,?,?,?,?)';
        $values = array(
            $event->getId(),
            $event->getTitle(),
            $event->getDescription(),
            $event->getDate(),
            $event->getLocation(),
            $event->getSponsor()->getId()
        );
        return $this->getConnection()->exec($sql, $values);
    }

    /**
     * @param $event Event
     * @return bool
     */
    public function updateEvent($event) {
        $sql = 'UPDATE ' . EventTable::TABLE_NAME . ' SET ';
        $sql .= EventTable::TITLE . ' = ?,';
        $sql .= EventTable::DESCRIPTION . ' = ?,';
        $sql .= EventTable::DATE . ' = ?,';
        $sql .= EventTable::LOCATION . ' = ?,';
        $sql .= EventTable::SPONSOR . ' = ? ';
        $sql .= 'WHERE ' . EventTable::ID . ' = ?';
        $params = array(
            $event->getTitle(),
            $event->getDescription(),
            $event->getDate(),
            $event->getLocation(),
            $event->getSponsor()->getId(),
            $event->getId()
        );
        return $this->getConnection()->exec($sql, $params);
    }

    private function convertToEvent($row) {
        $e = new Event($row[EventTable::ID]);
        $e->setTitle($row[EventTable::TITLE]);
        $e->setDescription($row[EventTable::DESCRIPTION]);
        $e->setDate($row[EventTable::DATE]);
        $e->setLocation($row[EventTable::LOCATION]);
        $am = AllianceMemberDao::convertToAllianceMember($row, EventTable::SPONSOR . '_');
        return $e;
    }

    private function generateJoinedSelect() {
        $sql = 'SELECT ' . EventTable::TABLE_ALIAS . '.*, ';
        $sql .= AmTable::aliased(AmTable::ID) . ' AS ' . self::SPONSOR_ID . ', ';
        $sql .= AmTable::aliased(AmTable::NAME) . ' AS ' . self::SPONSOR_NAME . ', ';
        $sql .= AmTable::aliased(AmTable::DESCRIPTION) . ' AS ' . self::SPONSOR_DESCRIPTION . ', ';
        $sql .= AmTable::aliased(AmTable::URL) . ' AS ' . self::SPONSOR_URL . ', ';
        $sql .= AmTable::aliased(AmTable::HEAD) . ' AS ' . self::SPONSOR_HEAD_ID . ' ';
        $sql .= UserTable::aliased(UserTable::NAME) . ' AS ' . self::SPONSOR_HEAD_NAME;
        $sql .= UserTable::aliased(UserTable::ONID) . ' AS ' . self::SPONSOR_HEAD_ONID;
        $sql .= UserTable::aliased(UserTable::ROLE) . ' AS ' . self::SPONSOR_HEAD_ROLE;
        $sql .= UserTable::aliased(UserTable::LAST_LOGIN) . ' AS ' . self::SPONSOR_HEAD_LAST_LOGIN;
        $sql .= 'FROM ' . EventTable::TABLE_NAME . ' ' . EventTable::TABLE_ALIAS . ' ';
        $sql .= 'INNER JOIN ' . AmTable::TABLE_NAME . ' ' . AmTable::TABLE_ALIAS . ' ';
        $sql .= 'ON ' . AmTable::aliased(AmTable::ID) . ' = ' . EventTable::aliased(EventTable::SPONSOR);
        $sql .= 'INNER JOIN ' . UserTable::TABLE_NAME . ' ' . UserTable::TABLE_ALIAS . ' ';
        $sql .= 'ON ' . UserTable::aliased(UserTable::ID) . ' = ' . AmTable::aliased(AmTable::HEAD);
        return $sql;
    }

    private const SPONSOR_ID = EventTable::SPONSOR . '_' . AmTable::ID;
    private const SPONSOR_NAME = EventTable::SPONSOR . '_' . AmTable::NAME;
    private const SPONSOR_DESCRIPTION = EventTable::SPONSOR . '_' . AmTable::DESCRIPTION;
    private const SPONSOR_URL = EventTable::SPONSOR . '_' . AmTable::URL;
    private const SPONSOR_HEAD_ID = EventTable::SPONSOR . '_' . AmTable::HEAD . '_' . UserTable::ID;
    private const SPONSOR_HEAD_NAME = EventTable::SPONSOR . '_' . AmTable::HEAD . '_' . UserTable::NAME;
    private const SPONSOR_HEAD_ONID = EventTable::SPONSOR . '_' . AmTable::HEAD . '_' . UserTable::ONID;
    private const SPONSOR_HEAD_ROLE = EventTable::SPONSOR . '_' . AmTable::HEAD . '_' . UserTable::ROLE;
    private const SPONSOR_HEAD_LAST_LOGIN = EventTable::SPONSOR . '_' . AmTable::HEAD . '_' . UserTable::LAST_LOGIN;
}