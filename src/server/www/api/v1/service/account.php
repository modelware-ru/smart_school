<?php

use MW\App\SessionObject;
use MW\Module\Account\Main as AccountModule;

function srv_sign_in($args)
{
    list($res, $data) = (new AccountModule())->signIn($args);

    if ($res->isOk()) {
        SessionObject::Instance()->setUserId($data['userId']);
        SessionObject::Instance()->setUserName($data['userName']);
        SessionObject::Instance()->setAccountId($data['accountId']);
        SessionObject::Instance()->setRoleId($data['roleId']);
        SessionObject::Instance()->setRoleStateId($data['roleStateId']);
    }
    return $res;
}










