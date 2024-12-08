<?php

namespace MW\Module\Domain\Task;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getTaskList()
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name, mt.topic_id, mtc.name topic_name,
(SELECT COUNT(mst.id) FROM main__serie_task mst WHERE mst.task_id = mt.id) mst_count
FROM main__task mt
JOIN main__topic mtc ON mtc.id = mt.topic_id
SQL;
        return $this->_db->select($stmt);
    }

    public function getTaskById($taskId)
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name, mt.topic_id
FROM main__task mt
WHERE mt.id = :taskId 
SQL;
        return $this->_db->select($stmt, ['taskId' => $taskId]);
    }

    public function createTask($name, $topicId)
    {
        $stmt = <<<SQL
INSERT INTO main__task (name, topic_id)
VALUES (:name, :topicId)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'topicId' => $topicId,
            ],
        ]);
    }

    public function updateTask($taskId, $name, $topicId)
    {
        $stmt = <<<SQL
UPDATE main__task SET name = :name, topic_id = :topicId
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $taskId,
                'name' => $name,
                'topicId' => $topicId,
            ]
        ]);
    }

    public function removeTask($taskId)
    {
        $stmt = <<<SQL
DELETE FROM main__task WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $taskId]);
    }

}
