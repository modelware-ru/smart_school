<?php

namespace MW\App;

use MW\Service\AuthZ\Role;
use MW\Shared\Session;

class SessionObject
{
    private const _USER_ID = 'userId';
    private const _USER_NAME = 'userName';
    private const _ACCOUNT_ID = 'accountId';
    private const _ROLE_ID = 'roleId';
    private const _ROLE_STATE_ID = 'roleStateId';
    private const _LANG_ID = 'langId';

    private static ?SessionObject $_Instance = null;

    private function __construct()
    {
        Session::Start();
    }

    public static function Instance()
    {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new SessionObject();
        }
        return self::$_Instance;
    }

    public function getRoleId()
    {
        return Session::Instance()->get(self::_ROLE_ID);
    }

    public function setRoleId($roleId)
    {
        Session::Instance()->set(self::_ROLE_ID, $roleId);
    }

    public function getRoleStateId()
    {
        return Session::Instance()->get(self::_ROLE_STATE_ID);
    }

    public function setRoleStateId($roleStateId)
    {
        Session::Instance()->set(self::_ROLE_STATE_ID, $roleStateId);
    }

    public function getLangId()
    {
        return Session::Instance()->get(self::_LANG_ID);
    }

    public function setLang($langId)
    {
        Session::Instance()->set(self::_LANG_ID, $langId);
    }

    public function getUserId()
    {
        return Session::Instance()->get(self::_USER_ID);
    }

    public function setUserId($userId)
    {
        Session::Instance()->set(self::_USER_ID, $userId);
    }

    public function getUserName()
    {
        return Session::Instance()->get(self::_USER_NAME);
    }

    public function setUserName($userName)
    {
        Session::Instance()->set(self::_USER_NAME, $userName);
    }

    public function getAccountId()
    {
        return Session::Instance()->get(self::_ACCOUNT_ID);
    }

    public function setAccountId($accountId)
    {
        Session::Instance()->set(self::_ACCOUNT_ID, $accountId);
    }

}
