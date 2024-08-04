<?php
namespace MW\Module\Account;

use MW\Shared\DBManager;

class Manager
{
    private $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

    public function getUserByLogin($login)
    {
        $stmt = <<<SQL
            SELECT id, account_id, password FROM main__user WHERE login = :login
            SQL;
        return $this->_db->select($stmt, ['login' => $login]);
    }

    // public function deleteSignUpCodeByHash($hash)
    // {
    //     $stmt = 'DELETE FROM main__signupCode WHERE hash = :hash';
    //     return $this->_db->delete($stmt, ['hash' => $hash]);
    // }

    // public function createUser($accountId, $email, $password, $name, $aboutMe, $langId)
    // {
    //     $stmt = <<<SQL
    //         INSERT INTO main__user (account_id, email, password, name, about_me, lang_id)
    //         VALUES (:accountId, :email, :password, :name, :aboutMe, :langId)
    //         SQL;
    //     return $this->_db->insert($stmt, [
    //         0 => [
    //             'accountId' => $accountId,
    //             'email' => $email,
    //             'password' => $password,
    //             'name' => $name,
    //             'aboutMe' => $aboutMe,
    //             'langId' => $langId,
    //         ],
    //     ]);
    // }

    // public function updateLangId($userId, $langId)
    // {
    //     $stmt = 'UPDATE main__user SET lang_id = :langId WHERE id = :userId';
    //     return $this->_db->update($stmt, ['langId' => $langId, 'userId' => $userId]);
    // }
}
