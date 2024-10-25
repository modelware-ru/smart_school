<?php

namespace MW\Module\Domain\Group;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getGroupList()
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mp.id parallel_id, mp.name parallel_name,
(SELECT COUNT(mug.id) FROM main__user_group mug WHERE mug.group_id = mg.id) mug_count,
(SELECT COUNT(ml.id) FROM main__lesson ml WHERE ml.group_id = mg.id) ml_count,
(SELECT COUNT(msgh.id) FROM main__student_group_Hist msgh WHERE msgh.group_id = mg.id) msgh_count
FROM main__group mg
JOIN main__parallel mp ON mp.id = mg.parallel_id
ORDER BY mp.`order`, mg.`order`
SQL;
        return $this->_db->select($stmt);
    }

    public function getGroupById($groupId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mg.parallel_id, mp.name parallel_name, mg.`order` group_order
FROM main__group mg
JOIN main__parallel mp ON mp.id = mg.parallel_id
WHERE mg.id = :groupId 
SQL;
        return $this->_db->select($stmt, ['groupId' => $groupId]);
    }

    public function getGroupListByParallelId($parallelId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name
FROM main__group mg
WHERE mg.parallel_id = :parallelId
SQL;
        return $this->_db->select($stmt, ['parallelId' => $parallelId]);
    }

    public function createGroup($name, $parallelId, $order)
    {
        $stmt = <<<SQL
INSERT INTO main__group (name, parallel_id, `order`)
VALUES (:name, :parallelId, :order)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'parallelId' => $parallelId,
                'order' => $order,
            ],
        ]);
    }

    public function updateGroup($groupId, $name, $parallelId, $order)
    {
        $stmt = <<<SQL
UPDATE main__group SET name = :name, parallel_id = :parallelId, `order` = :order
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $groupId,
                'name' => $name,
                'parallelId' => $parallelId,
                'order' => $order,
            ]
        ]);
    }

    public function removeGroup($groupId)
    {
        $stmt = <<<SQL
DELETE FROM main__group WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $groupId]);
    }

    public function removeTeacherListFromGroup($groupId)
    {
        $stmt = <<<SQL
DELETE FROM main__user_group WHERE group_id = :groupId
SQL;
        return $this->_db->delete($stmt, ['groupId' => $groupId]);
    }
}
