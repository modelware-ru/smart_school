<?php

namespace MW\Module\Account;

use MW\App\Setting;
use MW\Service\Authz\Main as AuthzService;
use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;


class Main
{
    const LOGIN_MAX_LENGTH = 50;

    public function signIn($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::signIn');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $login = $args['login'];
        $password = $args['password'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getUserByLogin($login);
        if (($cnt = count($resDb)) !== 1) {
            $localLog->info(MWI18nHelper::LogMessage(MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD, ["Найдено {$cnt} зап."]));
            $errorList['_msg_'] = [
                'code' => MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD,
            ];
            return [Util::MakeFailOperationResult($errorList), []];
        }

        $secret = Setting::Get('app.account.passSecret');
        $hash = Util::GenerateHash($password, "{$secret}");

        if ($hash !== $resDb[0]['password']) {
            $localLog->info(MWI18nHelper::LogMessage(MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD, ["Неверный пароль для логина ({$login})"]));
            $errorList['_msg_'] = [
                'code' => MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD,
            ];
            return [Util::MakeFailOperationResult($errorList), []];
        }

        $userId = $resDb[0]['id'];
        $accountId = $resDb[0]['account_id'];
        $userName = $resDb[0]['last_name'] . ' ' .  $resDb[0]['first_name'] . ' ' . $resDb[0]['middle_name'];

        $data = [
            'userId' => $userId,
            'userName' => $userName,
            'accountId' => $accountId,
            'roleId' => NULL,
            'roleStateId' => NULL,
        ];

        $resAr = AuthzService::GetMainRoleByAccountId(['accountId' => $accountId]);
        if (!empty($resAr)) {
            $data['roleId'] = $resAr['roleId'];
            $data['roleStateId'] = $resAr['roleStateId'];
        } else {
            $localLog->info(MWI18nHelper::LogMessage(MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD, ["Не найдено роли"]));
            $errorList['_msg_'] = [
                'code' => MWI18nHelper::MSG_WRONG_LOGIN_OR_PASSWORD,
            ];
            return [Util::MakeFailOperationResult($errorList), []];
        }

        $resAr = AuthzService::GetMainRoleByAccountId(['accountId' => $accountId]);

        return [Util::MakeSuccessOperationResult(test: $data), $data];
    }
}
