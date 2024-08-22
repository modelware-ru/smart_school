<?php

namespace MW\Service\Authz;

use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\MWI18nHelper;

class Main
{
    public static function GetPermissionList($args)
    {
        $localLog = Logger::Log()->withName('Service::Authz::GetPermissionList');
        $localLog->info('parameters:', $args);

        $accountId = $args['accountId'];
        $roleId = $args['roleId'];
        $roleStateId = $args['roleStateId'];
        $resourceType = $args['resourceType'];
        $resource = $args['resource'];
        $actionId = $args['actionId'];

        // test. start
        if (defined('PHPUNIT')) {
            // TODO: Подмена значений для тестов
        }
        // test. finish

        // check. start
        // - состояние не соответствует роли
        if (!Constant::CheckRoleStateId($roleId, $roleStateId)) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ["Несовместимость роли и состояния: '{$roleId}' - '{$roleStateId}'"],
            );
        }
        // - TODO: тип ресурса не соответствует ресурсу
        if (false /* || !Constant::CheckResourceType($resource, $resourceType) */) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ["Несовместимость ресурса и его типа: '{$resource}' - '{$resourceType}'"],
            );
        }
        // - TODO: нет такого типа ресурса
        // - TODO: действие не соответствует типу ресурса
        if (false /* || !Constant::CheckActionResourceType($action, $resourceType) */) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ["Несовместимость действия и типа ресурса: '{$action}' - '{$resourceType}'"],
            );
        }
        // check. finish

        $manager = new Manager();

        if (is_null($accountId)) {
            $resDb = $manager->getPermissionByRole($roleId, $roleStateId, $resource, $resourceType, $actionId);
        } else {
            $resDb = $manager->getPermissionByAccount($accountId, $roleId, $roleStateId, $resource, $resourceType, $actionId);
        }

        return array_map(function ($item) {
            return [
                'permission' => $item['permission'],
                'resource' => $item['resource'],
                'resourceType' => $item['resource_type'],
                'actionId' => $item['action_id'],
                'options' => \json_decode($item['options'], true),
            ];
        }, $resDb);
    }

    public static function GetMainRoleByAccountId($args)
    {
        $localLog = Logger::Log()->withName('Service::Authz::GetMainRoleByAccountId');
        $localLog->info('parameters:', $args);

        $accountId = $args['accountId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getRoleListByAccount($accountId);

        $res = [];
        if (count($resDb) !== 0) {
            $res ['roleId'] = $resDb[0]['role_id'];
            $res ['roleStateId'] = $resDb[0]['role_state_id'];
        }

        return $res;
    }

    public static function CreateAccount($args)
    {
        $localLog = Logger::Log()->withName('Service::Authz::CreateAccount');
        $localLog->info('parameters:', $args);

        $roleId = $args['roleId'];
        $roleStateId = $args['roleStateId'];

        // test. start
        if (defined('PHPUNIT')) {
            // TODO: Подмена значений для тестов
        }
        // test. finish

        // check. start
        // - состояние не соответствует роли
        if (!Constant::CheckRoleStateId($roleId, $roleStateId)) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ["Несовместимость роли и состояния: '{$roleId}' - '{$roleStateId}'"],
            );
        }
        // check. finish

        $manager = new Manager();

        $accountDb = $manager->createAccount();
        if (count($accountDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ['Ошибка при создании записи в таблице account'],
            );
        }

        $accountId = $accountDb[0];

        $accountRoleDb = $manager->createAccountRole($accountId, $roleId, $roleStateId);
        if (count($accountRoleDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ['Ошибка при создании записи в таблице account_role'],
            );
        }

        return [
            'accountId' => $accountId,
        ];
    }

    public static function RemoveAccount($args)
    {
        $localLog = Logger::Log()->withName('Service::Authz::RemoveAccount');
        $localLog->info('parameters:', $args);

        $accountId = $args['accountId'];

        // test. start
        if (defined('PHPUNIT')) {
            // TODO: Подмена значений для тестов
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();

        $resDb = $manager->removeAccountRole($accountId);
        $resDb = $manager->removeAccountGroup($accountId);
        $resDb = $manager->removeAccount($accountId);
        if ($resDb !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_UNKNOWN,
                logData: ["Ошибка при удалении записи в таблице account ({$accountId})"],
            );
        }

        return;
    }
}
