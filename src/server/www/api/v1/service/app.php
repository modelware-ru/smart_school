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

function app_save_group($args)
{
    list($res, $data) = (new DomainMain())->saveGroup($args);

    return $res;
}

function app_remove_group($args)
{
    list($res, $data) = (new DomainMain())->removeGroup($args);

    return $res;
}

function app_block_teacher($args)
{
    list($res, $data) = (new DomainMain())->blockTeacher($args);

    return $res;
}

function app_save_teacher($args)
{
    list($res, $data) = (new DomainMain())->saveTeacher($args);

    return $res;
}

function app_remove_teacher($args)
{
    list($res, $data) = (new DomainMain())->removeTeacher($args);

    return $res;
}

function app_save_subject($args)
{
    list($res, $data) = (new DomainMain())->saveSubject($args);

    return $res;
}

function app_remove_subject($args)
{
    list($res, $data) = (new DomainMain())->removeSubject($args);

    return $res;
}

function app_save_student($args)
{
    list($res, $data) = (new DomainMain())->saveStudent($args);

    return $res;
}

function app_remove_student($args)
{
    list($res, $data) = (new DomainMain())->removeStudent($args);

    return $res;
}

function app_change_class($args)
{
    list($res, $data) = (new DomainMain())->changeClass($args);

    return $res;
}

function app_change_group($args)
{
    list($res, $data) = (new DomainMain())->changeGroup($args);

    return $res;
}

function app_save_topic($args)
{
    list($res, $data) = (new DomainMain())->saveTopic($args);

    return $res;
}

function app_remove_topic($args)
{
    list($res, $data) = (new DomainMain())->removeTopic($args);

    return $res;
}