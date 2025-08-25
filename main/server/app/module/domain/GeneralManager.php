<?php

namespace MW\Module\Domain;

use MW\Shared\DBManager;

class GeneralManager
{
    protected $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

}
