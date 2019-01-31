<?php

namespace OSU\IOTA\DAO;

use OSU\IOTA\DAO\Tables\Material as MaterialTable;
use OSU\IOTA\DAO\Tables\MaterialType as MaterialTypeTable;
use OSU\IOTA\Model\Material;
use OSU\IOTA\Model\MaterialType;

class MaterialDao extends DAO {

    public function getMaterial($id) {
        $sql = $this->generateJoinedSelect();
        $results = $this->getConnection()->query($sql);
        foreach($results as $row) {
            $materials[] = self::extractMaterialFromRow($row);
        }
        return $materials;
    }

    private function getMaterials($sql) {
        $materials = [];
        $results = $this->getConnection()->query($sql);
        foreach($results as $row) {
            $materials[] = self::extractMaterialFromRow($row);
        }
        return $materials;
    }

    public function getMaterialsForEvent($eventId) {
        $sql = $this->generateJoinedSelect();
    }

    public function getMaterialsForUser($userId) {

    }

    public function createMaterial($material) {

    }

    public function updateMaterial($material) {

    }

    public function deleteMaterial($id) {

    }

    private function generateJoinedSelect() {
        $sql = 'SELECT * FROM ' . \implode(', ', [MaterialTable::TABLE_NAME_ALIAS, MaterialTypeTable::TABLE_NAME_ALIAS]) . ' ';
        $sql .= 'WHERE ' . MaterialTable::aliased(MaterialTable::TYPE) . ' = ' . MaterialTypeTable::aliased(MaterialTypeTable::ID);
        return $sql;
    }

    public static function extractMaterialFromRow($row) {
        $m = new Material($row[MaterialTable::ID]);
        $m->setName($row[MaterialTable::NAME]);
        $m->setDescription($row[MaterialTable::DESCRIPTION]);
        $mt = new MaterialType($row[MaterialTypeTable::ID]);
        $mt->setName($row[MaterialTypeTable::NAME]);
        $m->setType($mt);
        $m->setFile($row[MaterialTable::FILE]);
        return $m;
    }


}
