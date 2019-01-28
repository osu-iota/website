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
            $events[] = self::extractEventFromRow($row);
        }
        return $events;
    }

    public function getEvent($id) {
        $sql = $this->generateJoinedSelect();
        $sql .= ' AND ' . EventTable::aliased(EventTable::ID) . ' = ?';
        $result = $this->getConnection()->query($sql, [$id]);
        return self::extractEventFromRow($result[0]);
    }

    /**
     * @param $event Event
     * @return bool
     */
    public function createEvent($event) {
        $sql = 'INSERT INTO ' . EventTable::TABLE_NAME . ' VALUES(?,?,?,?,?,?)';
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

    private function extractEventFromRow($row) {
        $e = new Event($row[EventTable::ID]);
        $e->setTitle($row[EventTable::TITLE]);
        $e->setDescription($row[EventTable::DESCRIPTION]);
        $e->setDate($row[EventTable::DATE]);
        $e->setLocation($row[EventTable::LOCATION]);
        $am = AllianceMemberDao::extractAllianceMemberFromRow($row);
        $e->setSponsor($am);
        return $e;
    }

    private function generateJoinedSelect() {
        $sql = 'SELECT * ';
        $sql .= 'FROM ' . \implode(', ', [EventTable::TABLE_NAME_ALIAS, UserTable::TABLE_NAME_ALIAS, AmTable::TABLE_NAME_ALIAS]);
        $sql .= 'WHERE ' . EventTable::aliased(EventTable::SPONSOR) . ' = ' . AmTable::ID . ' ';
        $sql .= 'AND ' . AmTable::aliased(AmTable::HEAD) . ' = ' . UserTable::ID;
        return $sql;
    }
}