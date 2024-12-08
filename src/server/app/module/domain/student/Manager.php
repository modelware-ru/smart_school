<?php

namespace MW\Module\Domain\Student;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getStudentList()
    {
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name, t1.class_number, t1.class_letter, t1.class_parallel_id, t2.group_name, t2.group_parallel_id, t2.group_parallel_number,
(SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.student_id = ms.id) msch_count,
(SELECT COUNT(msgh.id) FROM main__student_group_Hist msgh WHERE msgh.student_id = ms.id) msgh_count,
(SELECT COUNT(msl.id) FROM main__student_lesson msl WHERE msl.student_id = ms.id) msl_count,
(SELECT COUNT(mss.id) FROM main__student_serie mss WHERE mss.student_id = ms.id) mss_count
FROM main__student ms

LEFT JOIN (
    SELECT ch.student_id, mp.`number` class_number, msch.letter class_letter, msch.parallel_id class_parallel_id
    FROM (
	    SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	    FROM main__student_class_Hist) ch
    JOIN main__student_class_Hist msch ON msch.id = ch.id
    JOIN main__parallel mp ON msch.parallel_id = mp.id 
    WHERE ch.start_date = ch.max_start_date AND ch.`order` = ch.max_order
) t1 ON t1.student_id = ms.id

LEFT JOIN (SELECT gh.student_id, mg.name group_name, mg.parallel_id group_parallel_id, mp.number group_parallel_number
FROM (
	SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	FROM main__student_group_Hist) 
gh
JOIN main__student_group_Hist msgh ON msgh.id = gh.id
JOIN main__group mg ON msgh.group_id = mg.id 
JOIN main__parallel mp ON mg.parallel_id = mp.id 
WHERE gh.start_date = gh.max_start_date AND gh.`order` = gh.max_order) t2 
ON t2.student_id = ms.id
ORDER BY t1.class_number, t1.class_letter, ms.last_name, ms.first_name, ms.middle_name
SQL;
        return $this->_db->select($stmt);
    }

    public function getStudentById($studentId)
    {
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name
FROM main__student ms
WHERE ms.id = :studentId 
SQL;
        return $this->_db->select($stmt, ['studentId' => $studentId]);
    }

    public function getStudentByIdList($studentIdList)
    {
        $studentIdListString = implode(',', $studentIdList);
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name, t1.class_number, t1.class_letter, t1.class_parallel_id
FROM main__student ms
LEFT JOIN (
    SELECT ch.student_id, mp.`number` class_number, msch.letter class_letter, msch.parallel_id class_parallel_id
    FROM (
	    SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	    FROM main__student_class_Hist) ch
    JOIN main__student_class_Hist msch ON msch.id = ch.id
    JOIN main__parallel mp ON msch.parallel_id = mp.id 
    WHERE ch.start_date = ch.max_start_date AND ch.`order` = ch.max_order
) t1 ON t1.student_id = ms.id
WHERE ms.id IN ({$studentIdListString})
ORDER BY ms.last_name, ms.first_name, ms.middle_name
SQL;
        return $this->_db->select($stmt);
    }

    public function createStudent($firstName, $lastName, $middleName)
    {
        $stmt = <<<SQL
INSERT INTO main__student (first_name, last_name, middle_name)
VALUES (:firstName, :lastName, :middleName)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
            ],
        ]);
    }

    public function updateStudent($studentId, $firstName, $lastName, $middleName)
    {
        $stmt = <<<SQL
UPDATE main__student SET 
first_name = :firstName,
last_name = :lastName,
middle_name = :middleName
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $studentId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
            ]
        ]);
    }

    public function removeStudent($studentId)
    {
        $stmt = <<<SQL
DELETE FROM main__student WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $studentId]);
    }

    public function getMaxOrderForStudentClassHistory($studentIdList, $startDate)
    {
        $sl = array_reduce($studentIdList, function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        }, []);

        $studentIdListString = implode(',', $sl);

        $stmt = <<<SQL
SELECT msch.student_id, MAX(`order`) max_order FROM main__student_class_Hist msch 
WHERE msch.start_date = :startDate
AND msch.student_id IN ({$studentIdListString})
GROUP BY msch.student_id
SQL;
        return $this->_db->select($stmt, ['startDate' => $startDate]);
    }

    public function addStudentClassHistory($studentIdList, $startDate, $parallelId, $classLetter, $reason)
    {
        $stmt = <<<SQL
INSERT INTO main__student_class_Hist (student_id, start_date, parallel_id, letter, reason, `order`)
VALUES (:id, :startDate, :parallelId, :classLetter, :reason, :order)
SQL;
        return $this->_db->insert(
            $stmt,
            $studentIdList,
            [
                'startDate' => $startDate,
                'parallelId' => $parallelId,
                'classLetter' => $classLetter,
                'reason' => $reason,
            ],
        );
    }

    public function getMaxOrderForStudentGroupHistory($studentIdList, $startDate)
    {
        $sl = array_reduce($studentIdList, function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        }, []);

        $studentIdListString = implode(',', $sl);

        $stmt = <<<SQL
SELECT msgh.student_id, MAX(`order`) max_order FROM main__student_group_Hist msgh 
WHERE msgh.start_date = :startDate
AND msgh.student_id IN ({$studentIdListString})
GROUP BY msgh.student_id
SQL;
        return $this->_db->select($stmt, ['startDate' => $startDate]);
    }

    public function addStudentGroupHistory($studentIdList, $startDate, $groupId, $reason)
    {
        $stmt = <<<SQL
INSERT INTO main__student_group_Hist (student_id, start_date, group_id, reason, `order`)
VALUES (:id, :startDate, :groupId, :reason, :order)
SQL;
        return $this->_db->insert(
            $stmt,
            $studentIdList,
            [
                'startDate' => $startDate,
                'groupId' => $groupId,
                'reason' => $reason,
            ],
        );
    }

    public function getStudentClassGroupHistory($studentId)
    {
        $stmt = <<<SQL
SELECT msch.id class_history_id, 0 group_history_id, msch.start_date, msch.`order`, CONCAT(mp.number, msch.letter) class_name, '' group_name, reason FROM main__student_class_Hist msch 
JOIN main__parallel mp ON mp.id = msch.parallel_id 
WHERE msch.student_id = :studentId1
UNION
SELECT 0 class_history_id, msgh.id group_history_id, msgh.start_date, msgh.`order`, '' class_name, CONCAT('[', mp.`number`, '] ', mg.name) group_name, reason FROM main__student_group_Hist msgh 
JOIN main__group mg ON mg.id = msgh.group_id 
JOIN main__parallel mp ON mp.id = mg.parallel_id 
WHERE msgh.student_id = :studentId2
ORDER BY start_date
SQL;
        return $this->_db->select($stmt, ['studentId1' => $studentId, 'studentId2' => $studentId]);
    }

    public function getStudentSerieById($studentSerieId)
    {
        $stmt = <<<SQL
SELECT
mss.type serie_type, mss.date serie_date, mss.serie_id serie_id,
mst.id student_id, mst.first_name, mst.last_name, mst.middle_name,
msr.name serie_name,
ml.date lesson_date,
ms.name subject_name,
mg.name group_name,
mp.name parallel_name, mp.number parallel_number
FROM main__student_serie mss
JOIN main__student mst ON mst.id = mss.student_id
JOIN main__serie msr ON msr.id = mss.serie_id
LEFT JOIN main__lesson ml ON ml.id = mss.lesson_id
LEFT JOIN main__subject ms ON ms.id = ml.subject_id
LEFT JOIN main__group mg ON mg.id = ml.group_id
LEFT JOIN main__parallel mp ON mp.id = mg.parallel_id
WHERE mss.id = :studentSerieId 
SQL;
        return $this->_db->select($stmt, ['studentSerieId' => $studentSerieId]);
    }

    public function getStudentSolutionById($studentSerieId)
    {
        $stmt = <<<SQL
SELECT mst.id serie_task_id, 
mssst.id solution_id, mssst.value solution_value, mssst.date solution_date, 
mt.name task_name
FROM main__student_serie mss
JOIN main__serie_task mst ON mst.serie_id = mss.serie_id
JOIN main__task mt ON mt.id = mst.task_id
LEFT JOIN main__studentSerie_serieTask mssst ON mssst.serie_task_id = mst.id AND mssst.student_serie_id = :studentSerieId1
WHERE mss.id = :studentSerieId2
SQL;
        return $this->_db->select($stmt, ['studentSerieId1' => $studentSerieId, 'studentSerieId2' => $studentSerieId]);
    }

    public function removeStudentSolution($solutionList)
    {
        $stmt = <<<SQL
DELETE FROM main__studentSerie_serieTask WHERE id = :solutionId
SQL;
        return $this->_db->deleteEx($stmt, $solutionList);
    }

    public function updateStudentSolution($solutionList, $date)
    {
        $stmt = <<<SQL
UPDATE main__studentSerie_serieTask SET 
value = :value,
date = :date
WHERE id = :solutionId
SQL;
        return $this->_db->update($stmt, $solutionList, ['date' => $date]);
    }

    public function createStudentSolution($studentSerieId, $solutionList, $date)
    {
        $stmt = <<<SQL
INSERT INTO main__studentSerie_serieTask (student_serie_id, serie_task_id, value, `date`)
VALUES (:studentSerieId, :serieTaskId, :value, :date)
SQL;
        return $this->_db->insert($stmt, $solutionList, [
            'date' => $date,
            'studentSerieId' => $studentSerieId,
        ]);
    }

    public function getStudentListForGroup($groupId, $startDate, $finishDate)
    {
        $stmt = <<<SQL
SELECT tbl2.student_id, ms.first_name, ms.last_name, ms.middle_name, tbl2.start_date, tbl2.finish_date FROM ( 
	SELECT tbl1.student_id, MIN(tbl1.start_date) start_date, (MAX(tbl1.finish_date) - INTERVAL 1 DAY) finish_date 
	FROM (
		SELECT
		msgh.student_id,
		msgh.group_id,
		msgh.start_date,
		IFNULL(LEAD(msgh.start_date, 1) OVER (PARTITION BY msgh.student_id ORDER BY msgh.start_date), CURRENT_DATE() + INTERVAL 1 DAY) finish_date
		FROM main__student_group_Hist msgh
		ORDER BY msgh.student_id, msgh.start_date
	) tbl1
	WHERE tbl1.group_id = :groupId
	GROUP BY tbl1.student_id
) tbl2
JOIN main__student ms ON ms.id = tbl2.student_id
WHERE tbl2.start_date <= :finishDate AND tbl2.finish_date >= :startDate
SQL;
        return $this->_db->select($stmt, ['groupId' => $groupId, 'startDate' => $startDate, 'finishDate' => $finishDate]);
    }

    public function getStudentSerieGroupList($studentId, $groupId)
    {
        $stmt = <<<SQL
SELECT
mss.id student_serie_id,
mss.serie_id,
mss.group_id,
mss.type serie_type,
msr.name serie_name,
mss.date serie_date,
ml.`date` lesson_date,
ms.name subject_name
FROM main__student_serie mss
JOIN main__serie msr ON msr.id = mss.serie_id
LEFT JOIN main__lesson ml ON ml.id = mss.lesson_id
LEFT JOIN main__subject ms ON ms.id = ml.subject_id
WHERE mss.student_id = :studentId AND mss.group_id = :groupId
SQL;
        return $this->_db->select($stmt, ['studentId' => $studentId, 'groupId' => $groupId]);
    }
}
