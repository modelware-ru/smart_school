<?php

use MW\App\SessionObject;
use MW\Module\Account\Main;

function app_sign_in($args)
{
    list($res, $data) = (new Main())->signIn($args);

    if ($res->isOk()) {
        SessionObject::Instance()->setUserId($data['userId']);
        SessionObject::Instance()->setAccountId($data['accountId']);
        SessionObject::Instance()->setRoleId($data['roleId']);
        SessionObject::Instance()->setRoleStateId($data['roleStateId']);
    }
    return $res;
}
