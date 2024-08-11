<?php

namespace MW\Module\Domain;

use MW\Shared\DBManager;

class Manager
{
    private $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

    public function getParallelList()
    {
        $stmt = <<<SQL
SELECT mp.id parallel_id, mp.name, mp.number, mp.show_in_group,
(SELECT COUNT(mg.id) FROM main__group mg WHERE mg.parallel_id = mp.id) mg_count,
(SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.parallel_id = mp.id) msch_count
FROM main__parallel mp
SQL;
        return $this->_db->select($stmt);
    }

    public function getParallelById($parallelId)
    {
        $stmt = <<<SQL
SELECT mp.id, mp.name, mp.number, mp.show_in_group
FROM main__parallel mp
WHERE mp.id = :parallelId 
SQL;
        return $this->_db->select($stmt, ['parallelId' => $parallelId]);
    }

    public function createParallel($name, $number, $showInGroup)
    {
        $stmt = <<<SQL
INSERT INTO main__parallel (name, number, show_in_group)
VALUES (:name, :number, :showInGroup)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
            ],
        ]);
    }

    public function updateParallel($id, $name, $number, $showInGroup)
    {
        $stmt = <<<SQL
UPDATE main__parallel SET name = :name, number = :number, show_in_group = :showInGroup 
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $id,
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
            ]
        ]);
    }

    public function removeParallel($id)
    {
        $stmt = <<<SQL
DELETE FROM main__parallel WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $id]);
    }

    public function getGroupList()
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mp.name parallel_name,
(SELECT COUNT(mug.id) FROM main__user_group mug WHERE mug.group_id = mg.id) mug_count,
(SELECT COUNT(ml.id) FROM main__lesson ml WHERE ml.group_id = mg.id) ml_count,
(SELECT COUNT(msgh.id) FROM main__student_group_Hist msgh WHERE msgh.group_id = mg.id) msgh_count
FROM main__group mg
JOIN main__parallel mp ON mp.id = mg.parallel_id
SQL;
        return $this->_db->select($stmt);
    }

    public function getGroupById($groupId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mg.parallel_id
FROM main__group mg
WHERE mg.id = :groupId 
SQL;
        return $this->_db->select($stmt, ['groupId' => $groupId]);
    }

    public function createGroup($name, $parallelId)
    {
        $stmt = <<<SQL
INSERT INTO main__group (name, parallel_id)
VALUES (:name, :parallelId)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'parallelId' => $parallelId,
            ],
        ]);
    }

    public function updateGroup($id, $name, $parallelId)
    {
        $stmt = <<<SQL
UPDATE main__group SET name = :name, parallel_id = :parallelId
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $id,
                'name' => $name,
                'parallelId' => $parallelId,
            ]
        ]);
    }

    public function removeGroup($id)
    {
        $stmt = <<<SQL
DELETE FROM main__group WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $id]);
    }

}
