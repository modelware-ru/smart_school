<?php

namespace MW\Module\Domain\Subject;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getSubjectList()
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name,
(SELECT COUNT(ml.id) FROM main__lesson ml WHERE ml.subject_id = ms.id) ml_count
FROM main__subject ms
SQL;
        return $this->_db->select($stmt);
    }

    public function getSubjectById($subjectId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name
FROM main__subject ms
WHERE ms.id = :subjectId 
SQL;
        return $this->_db->select($stmt, ['subjectId' => $subjectId]);
    }

    public function createSubject($name)
    {
        $stmt = <<<SQL
INSERT INTO main__subject (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateSubject($subjectId, $name)
    {
        $stmt = <<<SQL
UPDATE main__subject SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $subjectId,
                'name' => $name,
            ]
        ]);
    }

    public function removeSubject($subjectId)
    {
        $stmt = <<<SQL
DELETE FROM main__subject WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $subjectId]);
    }

}
