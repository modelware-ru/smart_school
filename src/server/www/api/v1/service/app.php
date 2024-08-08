<?php

use MW\App\SessionObject;
use MW\Module\Account\Main as AccountMain;
use MW\Module\Domain\Main as DomainMain;

function app_sign_in($args)
{
    list($res, $data) = (new AccountMain())->signIn($args);

    if ($res->isOk()) {
        SessionObject::Instance()->setUserId($data['userId']);
        SessionObject::Instance()->setAccountId($data['accountId']);
        SessionObject::Instance()->setRoleId($data['roleId']);
        SessionObject::Instance()->setRoleStateId($data['roleStateId']);
    }
    return $res;
}

function app_save_parallel($args)
{
    list($res, $data) = (new DomainMain())->saveParallel($args);

    return $res;
}

function app_remove_parallel($args)
{
    list($res, $data) = (new DomainMain())->removeParallel($args);

    return $res;
}