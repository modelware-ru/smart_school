<?php

namespace MW\Module\Domain\Serie;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getSerieList()
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name,
(SELECT COUNT(mst.id) FROM main__serie_task mst WHERE mst.serie_id = ms.id) mst_count,
(SELECT COUNT(mss.id) FROM main__student_serie mss WHERE mss.serie_id = ms.id) mss_count,
(SELECT COUNT(mls.id) FROM main__lesson_serie mls WHERE mls.serie_id = ms.id) mls_count
FROM main__serie ms
SQL;
        return $this->_db->select($stmt);
    }

    public function getSerieById($serieId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name
FROM main__serie ms
WHERE ms.id = :serieId 
SQL;
        return $this->_db->select($stmt, ['serieId' => $serieId]);
    }

    public function getSerieTaskListById($serieId)
    {

        $stmt = <<<SQL
SELECT mt.id, mt.name
FROM main__serie_task mst
JOIN main__task mt ON mt.id = mst.task_id
WHERE mst.serie_id = :serieId
SQL;
        return $this->_db->select($stmt, ['serieId' => $serieId]);
    }

    public function createSerie($name)
    {
        $stmt = <<<SQL
INSERT INTO main__serie (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateSerie($serieId, $name)
    {
        $stmt = <<<SQL
UPDATE main__serie SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $serieId,
                'name' => $name,
            ]
        ]);
    }

    public function removeTaskListFromSerie($removedTaskIdList, $serieId)
    {
        $removedTaskIdListString = implode(',', $removedTaskIdList);

        $stmt = <<<SQL
DELETE FROM main__serie_task WHERE task_id IN ({$removedTaskIdListString}) AND serie_id = :serieId
SQL;
        return $this->_db->delete($stmt, ['serieId' => $serieId]);
    }

    public function createTaskList($newTaskList)
    {
        $tl = array_map(function ($item) {
            return [
                'name' => $item,
            ];
        }, $newTaskList);

        // TODO: topic
        $stmt = <<<SQL
INSERT INTO main__task (name, topic_id)
VALUES (:name, :topicId)
SQL;
        return $this->_db->insert(
            $stmt,
            $tl,
            [
                'topicId' => 1,
            ],
            true
        );
    }

    public function fetchTaskList($taskList)
    {
        $taskListString = '"' . implode('","', $taskList) . '"';

        $stmt = <<<SQL
SELECT id FROM main__task WHERE name IN ({$taskListString})
SQL;
        return $this->_db->select($stmt);
    }

    public function addTaskListToSerie($newTaskIdList, $serieId)
    {
        $stmt = <<<SQL
INSERT INTO main__serie_task (serie_id, task_id)
VALUES (:serieId, :taskId)
SQL;
        return $this->_db->insert(
            $stmt,
            $newTaskIdList,
            [
                'serieId' => $serieId,
            ],
        );
    }

    public function removeSerie($serieId)
    {
        $stmt = <<<SQL
DELETE FROM main__serie WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $serieId]);
    }

    public function getSerieListInLesson($lessonId)
    {
        $stmt = <<<SQL
SELECT ms.id serie_id, ms.name serie_name
FROM main__lesson_serie mls
JOIN main__serie ms ON mls.serie_id = ms.id
WHERE mls.lesson_id = :lessonId
ORDER BY ms.name
SQL;
        return $this->_db->select($stmt, [
            'lessonId' => $lessonId
        ]);
    }
}
