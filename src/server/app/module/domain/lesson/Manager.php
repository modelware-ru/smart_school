<?php

namespace MW\Module\Domain\Lesson;


use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getLessonById($lessonId)
    {
        $stmt = <<<SQL
SELECT ml.id lesson_id, ml.date lesson_date, mg.parallel_id parallel_id, mg.id group_id, mg.name group_name, ms.id subject_id, ms.name subject_name
FROM main__lesson ml
JOIN main__subject ms ON ms.id = ml.subject_id
JOIN main__group mg ON mg.id = ml.group_id
WHERE ml.id = :lessonId 
SQL;
        return $this->_db->select($stmt, ['lessonId' => $lessonId]);
    }

    public function createLesson($date, $subjectId, $groupId)
    {
        $stmt = <<<SQL
INSERT INTO main__lesson (`date`, subject_id, group_id)
VALUES (:date, :subjectId, :groupId)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'date' => $date,
                'subjectId' => $subjectId,
                'groupId' => $groupId,
            ],
        ]);
    }

    public function updateLesson($lessonId, $date, $subjectId, $groupId)
    {
        $stmt = <<<SQL
UPDATE main__lesson SET `date` = :date, subject_id = :subjectId, group_id = :groupId
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $lessonId,
                'date' => $date,
                'subjectId' => $subjectId,
                'groupId' => $groupId,
            ]
        ]);
    }

    public function removeLesson($lessonId)
    {
        $stmt = <<<SQL
DELETE FROM main__lesson WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $lessonId]);
    }

    public function removeSerieListFromLesson($lessonId)
    {
        $stmt = <<<SQL
DELETE FROM main__lesson_serie WHERE lesson_id = :lessonId
SQL;
        return $this->_db->delete($stmt, ['lessonId' => $lessonId]);
    }

    public function addSerieListToLesson($lessonId, $serieList)
    {
        $stmt = <<<SQL
INSERT INTO main__lesson_serie (lesson_id, serie_id) VALUES (:lessonId, :serieId);
SQL;
        return $this->_db->insert($stmt, $serieList, ['lessonId' => $lessonId]);
    }

    public function getStudentListForLesson($lessonId, $lessonDate, $parallelId, $groupId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.first_name, ms.last_name, ms.middle_name, msl.note, msl.attendanceDict_id 
FROM main__student ms 
JOIN main__student_class_Hist msch ON msch.student_id = ms.id AND msch.id = 
(
	SELECT msch2.id FROM main__student_class_Hist msch2 
	WHERE msch2.start_date <= :lessonDate1
	AND msch2.student_id = ms.id
	ORDER BY msch2.start_date DESC, msch2.`order` DESC LIMIT 1
) AND msch.parallel_id = :parallelId
JOIN main__student_group_Hist msgh ON msgh.student_id = ms.id AND msgh.id = 
(
	SELECT msgh2.id FROM main__student_group_Hist msgh2 
	WHERE msgh2.start_date <= :lessonDate2
	AND msgh2.student_id = ms.id
	ORDER BY msgh2.start_date DESC, msgh2.`order` DESC LIMIT 1
) AND msgh.group_id = :groupId
LEFT JOIN main__student_lesson msl ON msl.lesson_id = :lessonId AND msl.student_id = ms.id
ORDER BY ms.last_name, ms.first_name, ms.middle_name
SQL;
        return $this->_db->select($stmt, [
            'lessonId' => $lessonId,
            'lessonDate1' => $lessonDate,
            'lessonDate2' => $lessonDate,
            'parallelId' => $parallelId,
            'groupId' => $groupId,
        ]);
    }

    public function getAttendanceDict()
    {
        $stmt = <<<SQL
SELECT mad.id, mad.name, mad.display, mad.default
FROM main__attendance_Dict mad
ORDER BY mad.default, mad.name
SQL;
        return $this->_db->select($stmt);
    }

    public function getLessonListForGroup($groupId, $startDate, $finishDate)
    {
        $where = '';
        $vars = [];
        $vars['groupId'] = $groupId;

        if (!is_null($startDate)) {
            $where = $where . ' AND ml.date >= :startDate';
            $vars['startDate'] = $startDate;
        }
        if (!is_null($finishDate)) {
            $where = $where . ' AND ml.date <= :finishDate';
            $vars['finishDate'] = $finishDate;
        }

        $stmt = <<<SQL
SELECT ml.id lesson_id, ml.date lesson_date, ms.id subject_id, ms.name subject_name,
(SELECT COUNT(msl.id) FROM main__student_lesson msl WHERE msl.lesson_id = ml.id) msl_count,
(SELECT COUNT(mls.id) FROM main__lesson_serie mls WHERE mls.lesson_id = ml.id) mls_count
FROM main__lesson ml
JOIN main__subject ms ON ms.id = ml.subject_id
WHERE ml.group_id = :groupId {$where}
ORDER BY ms.name, ml.date
SQL;
        return $this->_db->select($stmt, $vars);
    }

    public function getStudentSerieForLesson($studentId, $lessonId)
    {
        $stmt = <<<SQL
SELECT mss.id, mss.type serie_type, ms.id serie_id, ms.name serie_name, 
EXISTS (
    SELECT id FROM main__studentSerie_serieTask mssst WHERE mssst.student_serie_id = mss.id 
) as has_solution
FROM main__student_serie mss
JOIN main__serie ms ON ms.id = mss.serie_id
WHERE mss.lesson_id = :lessonId AND mss.student_id = :studentId
ORDER BY ms.name
SQL;
        return $this->_db->select($stmt, [
            'lessonId' => $lessonId,
            'studentId' => $studentId,
        ]);
    }

    public function addSerieToLesson($lessonId, $date, $serieId, $studentList, $groupId)
    {
        $stmt = <<<SQL
INSERT INTO main__student_serie (`type`, `date`, lesson_id, student_id, serie_id, group_id)
VALUES (:type, :date, :lessonId, :studentId, :serieId, :groupId)
SQL;
        return $this->_db->insert($stmt, $studentList, [
            'date' => $date,
            'lessonId' => $lessonId,
            'serieId' => $serieId,
            'groupId' => $groupId,
        ], true);
    }

    public function removeSerieFromLesson($groupId, $lessonId, $serieId, $studentList)
    {
        $stmt = <<<SQL
DELETE FROM main__student_serie WHERE group_id = :groupId AND lesson_id = :lessonId AND serie_id = :serieId AND student_id = :studentId AND type = :type
SQL;
        return $this->_db->deleteEx($stmt, $studentList, ['lessonId' => $lessonId, 'serieId' => $serieId, 'groupId' => $groupId]);
    }
}
