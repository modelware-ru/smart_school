<?php

namespace MW\Module\Domain\Task;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getTaskList()
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name, 
(SELECT COUNT(mst.id) FROM main__serie_task mst WHERE mst.task_id = mt.id) mst_count
FROM main__task mt
SQL;
        return $this->_db->select($stmt);
    }

    public function getTaskIdList($taskList)
    {
        $taskListString = '"' . implode('","', $taskList) . '"';

        $stmt = <<<SQL
SELECT id FROM main__task WHERE name IN ({$taskListString})
SQL;
        return $this->_db->select($stmt);
    }

    public function getTopicSubtopicListByTaskId($taskId)
    {
        $stmt = <<<SQL
SELECT mt.id topic_id, mt.name topic_name, ms.id subtopic_id, ms.name subtopic_name
FROM main__task_subtopic mts
JOIN main__subtopic ms ON ms.id = mts.subtopic_id
JOIN main__topic mt ON mt.id = ms.topic_id
WHERE mts.task_id = :taskId
SQL;
        return $this->_db->select($stmt, ['taskId' => $taskId]);
    }


    public function getTaskById($taskId)
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name
FROM main__task mt
WHERE mt.id = :taskId 
SQL;
        return $this->_db->select($stmt, ['taskId' => $taskId]);
    }

    public function createTask($name)
    {
        return $this->createTaskList([$name]);
        //         $stmt = <<<SQL
        // INSERT INTO main__task (name)
        // VALUES (:name)
        // SQL;
        //         return $this->_db->insert($stmt, [
        //             0 => [
        //                 'name' => $name,
        //             ],
        //         ]);
    }

    public function createTaskList($newTaskList)
    {
        $tl = array_map(function ($item) {
            return [
                'name' => $item,
            ];
        }, $newTaskList);

        $stmt = <<<SQL
INSERT INTO main__task (name)
VALUES (:name)
SQL;
        return $this->_db->insert(
            $stmt,
            $tl,
            [],
            true
        );
    }

    public function updateTask($taskId, $name)
    {
        $stmt = <<<SQL
UPDATE main__task SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $taskId,
                'name' => $name,
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


    public function removeTaskSubtopicList($taskId)
    {
        $stmt = <<<SQL
DELETE FROM main__task_subtopic WHERE task_id = :taskId
SQL;
        return $this->_db->delete($stmt, ['taskId' => $taskId]);
    }

    public function addSubtopicListToTask($newSubtopicList, $taskId)
    {
        $stmt = <<<SQL
INSERT INTO main__task_subtopic (task_id, subtopic_id)
VALUES (:taskId, :subtopicId)
SQL;
        return $this->_db->insert(
            $stmt,
            $newSubtopicList,
            [
                'taskId' => $taskId,
            ],
        );
    }
}
