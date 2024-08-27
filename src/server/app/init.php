<?php
use MW\App\SessionObject;
use MW\App\Setting;
use MW\Shared\Session;

// используется при передаче параметров в шаблоны.
$templateData = [];

$userId = SessionObject::Instance()->getUserId();

if (is_null($userId)) {
    Session::Instance()->reset();
}

$accountId = SessionObject::Instance()->getAccountId();
$roleId = SessionObject::Instance()->getRoleId();
$roleStateId = SessionObject::Instance()->getRoleStateId();
$langId = SessionObject::Instance()->getLangId();

if (is_null($langId)) {
    $langId = Setting::Get('app.defaultLangId');
    SessionObject::Instance()->setLang($langId);
}

if (is_null($roleId) || is_null($roleStateId)) {
    $roleId = Setting::Get('authz.defaultRoleId');
    SessionObject::Instance()->setRoleId($roleId);
    $roleStateId = Setting::Get('authz.defaultRoleStateId');
    SessionObject::Instance()->setRoleStateId($roleStateId);
}

$requestUID = uniqid(time(), true);
$startTime = microtime(true);
set_exception_handler('\MW\Shared\MWException::DefaultExceptionHandler');
