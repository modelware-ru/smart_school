<?php

namespace MW\Module\Domain\Parallel;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getParallelList()
    {
        $stmt = <<<SQL
SELECT mp.id, mp.name, mp.number, mp.show_in_group, mp.`order`,
(SELECT COUNT(mg.id) FROM main__group mg WHERE mg.parallel_id = mp.id) mg_count,
(SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.parallel_id = mp.id) msch_count
FROM main__parallel mp
ORDER BY mp.`order`
SQL;
        return $this->_db->select($stmt);
    }

    public function getParallelById($parallelId)
    {
        $stmt = <<<SQL
SELECT mp.id, mp.name, mp.number, mp.show_in_group, mp.`order`
FROM main__parallel mp
WHERE mp.id = :parallelId 
SQL;
        return $this->_db->select($stmt, ['parallelId' => $parallelId]);
    }

    public function createParallel($name, $number, $showInGroup, $order)
    {
        $stmt = <<<SQL
INSERT INTO main__parallel (name, number, show_in_group, `order`)
VALUES (:name, :number, :showInGroup, :order)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
                'order' => $order,
            ],
        ]);
    }

    public function updateParallel($parallelId, $name, $number, $showInGroup, $order)
    {
        $stmt = <<<SQL
UPDATE main__parallel SET name = :name, number = :number, show_in_group = :showInGroup, `order` = :order
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $parallelId,
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
                'order' => $order,
            ]
        ]);
    }

    public function removeParallel($parallelId)
    {
        $stmt = <<<SQL
DELETE FROM main__parallel WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $parallelId]);
    }
}
