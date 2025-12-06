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
            SELECT ms.id, ms.name, ms.max_value_class_writing, ms.max_value_class_verbal, ms.max_value_home_writing,  ms.max_value_home_verbal
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

    public function createSerie($name, $maxValueClassWriting, $maxValueClassVerbal, $maxValueHomeWriting, $maxValueHomeVerbal)
    {
        $stmt = <<<SQL
            INSERT INTO main__serie (name, max_value_class_writing, max_value_class_verbal, max_value_home_writing, max_value_home_verbal)
            VALUES (:name, :maxValueClassWriting, :maxValueClassVerbal, :maxValueHomeWriting, :maxValueHomeVerbal)
            SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'maxValueClassWriting' => $maxValueClassWriting,
                'maxValueClassVerbal' => $maxValueClassVerbal,
                'maxValueHomeWriting' => $maxValueHomeWriting,
                'maxValueHomeVerbal' => $maxValueHomeVerbal,
            ],
        ]);
    }

    public function updateSerie($serieId, $name, $maxValueClassWriting, $maxValueClassVerbal, $maxValueHomeWriting, $maxValueHomeVerbal)
    {
        $stmt = <<<SQL
            UPDATE main__serie SET name = :name, max_value_class_writing = :maxValueClassWriting, max_value_class_verbal = :maxValueClassVerbal, max_value_home_writing = :maxValueHomeWriting, max_value_home_verbal = :maxValueHomeVerbal
            WHERE id = :id
            SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $serieId,
                'name' => $name,
                'maxValueClassWriting' => $maxValueClassWriting,
                'maxValueClassVerbal' => $maxValueClassVerbal,
                'maxValueHomeWriting' => $maxValueHomeWriting,
                'maxValueHomeVerbal' => $maxValueHomeVerbal,
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

    public function addHomeSerieToStudent($studentId, $serieId, $groupId, $date)
    {
        $stmt = <<<SQL
            INSERT INTO main__student_serie (type, student_id, group_id, date, serie_id, max_value_writing, max_value_verbal)
            VALUES ('HOME', :studentId, :groupId, :date, :serieId1, 
            (SELECT max_value_home_writing as max_value_writing FROM main__serie WHERE id = :serieId2),
            (SELECT max_value_home_verbal as max_value_verbal FROM main__serie WHERE id = :serieId3))
            SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'studentId' => $studentId,
                'groupId' => $groupId,
                'date' => $date,
                'serieId1' => $serieId,
                'serieId2' => $serieId,
                'serieId3' => $serieId,
            ],
        ]);
    }

    public function removeHomeSerieFromStudent($studentSerieId)
    {
        $stmt = <<<SQL
            DELETE FROM main__student_serie WHERE id = :id
            SQL;
        return $this->_db->delete($stmt, ['id' => $studentSerieId]);
    }
}
