<?php

namespace MW\Module\Domain\SchoolYear;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getSchoolYearList()
    {
        $stmt = <<<SQL
SELECT msy.id, msy.name, msy.start_date, msy.finish_date, msy.is_current,
(SELECT COUNT(mug.id) FROM main__user_group mug WHERE mug.schoolYear_id = msy.id) mug_count
FROM main__schoolYear msy
ORDER BY msy.start_date DESC
SQL;
        return $this->_db->select($stmt);
    }


    public function getCurrentSchoolYearAndCount()
    {
        $stmt = <<<SQL
SELECT 
(SELECT msy.id FROM main__schoolYear msy WHERE is_current = 'Y') current_id,
(SELECT COUNT(msy.id) FROM main__schoolYear msy) `count`;
SQL;
        return $this->_db->select($stmt);
    }

    public function getSchoolYearById($schoolYearId)
    {
        $stmt = <<<SQL
SELECT msy.id, msy.name, msy.start_date, msy.finish_date, msy.is_current
FROM main__schoolYear msy
WHERE msy.id = :schoolYearId 
SQL;
        return $this->_db->select($stmt, ['schoolYearId' => $schoolYearId]);
    }

    public function createSchoolYear($name, $startDate, $finishDate, $isCurrent)
    {
        $stmt = <<<SQL
INSERT INTO main__schoolYear (name, start_date, finish_date, is_current)
VALUES (:name, :startDate, :finishDate, :isCurrent)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'startDate' => $startDate,
                'finishDate' => $finishDate,
                'isCurrent' => $isCurrent,
            ],
        ]);
    }

    public function updateSchoolYear($schoolYearId, $name, $startDate, $finishDate, $isCurrent)
    {
        $stmt = <<<SQL
UPDATE main__schoolYear SET name = :name, start_date = :startDate, finish_date = :finishDate, is_current = :isCurrent
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $schoolYearId,
                'name' => $name,
                'startDate' => $startDate,
                'finishDate' => $finishDate,
                'isCurrent' => $isCurrent,
            ]
        ]);
    }

    public function updateSchoolYearIsCurrent($schoolYearId, $isCurrent)
    {
        $stmt = <<<SQL
UPDATE main__schoolYear SET is_current = :isCurrent
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $schoolYearId,
                'isCurrent' => $isCurrent,
            ]
        ]);
    }


    public function removeSchoolYear($schoolYearId)
    {
        $stmt = <<<SQL
DELETE FROM main__schoolYear WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $schoolYearId]);
    }

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

}
