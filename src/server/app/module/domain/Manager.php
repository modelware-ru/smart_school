<?php

namespace MW\Module\Domain;

use MW\Shared\DBManager;

class Manager
{
    private $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

    public function getParallelList()
    {
        $stmt = <<<SQL
            SELECT mp.id parallel_id, mp.name_text, mp.name_number, mp.show_in_group,
            (SELECT COUNT(mg.id) FROM main__group mg WHERE mg.parallel_id = mp.id) mg_count,
            (SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.parallel_id = mp.id) msch_count
            FROM main__parallel mp
            SQL;
        return $this->_db->select($stmt);
    }
}
