<?php

namespace MW\Module\Domain;

use MW\App\Setting;
use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;
use MW\Service\Authz\Main as AuthzService;
use MW\Service\Authz\Constant as AuthzConstant;


class Main
{
    const PARALLEL_NAME_MAX_LENGTH = 100;
    const PARALLEL_NUMBER_LENGTH = 10;
    const GROUP_NAME_MAX_LENGTH = 100;
    const LOGIN_MAX_LENGTH = 50;
    const EMAIL_MAX_LENGTH = 100;
    const PASSWORD_MAX_LENGTH = 20;
    const FIRST_NAME_MAX_LENGTH = 100;
    const LAST_NAME_MAX_LENGTH = 100;
    const MIDDLE_NAME_MAX_LENGTH = 100;
    const SUBJECT_NAME_MAX_LENGTH = 100;

    public function getParallelList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getParallelList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getParallelList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'number' => $item['number'],
                'showInGroup' => $item['show_in_group'] === 'Y',
                'canBeRemoved' => ($item['mg_count'] + $item['msch_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getParallelById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getParallelById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $parallelId = $args['parallelId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getParallelById($parallelId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Параллель с id = {$parallelId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
            'number' => $resDb[0]['number'],
            'showInGroup' => $resDb[0]['show_in_group'] === 'Y',
        ];

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveParallel($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::saveParallel');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $number = $args['number'];
        $showInGroup = $args['showInGroup'] ? 'Y' : 'N';

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::PARALLEL_NAME_MAX_LENGTH)->check();

        $errorList = [];
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::PARALLEL_NAME_MAX_LENGTH],
            ];
        }

        $numberCheck = (new ValueChecker($number))->notEmpty()->lengthLessOrEqual(self::PARALLEL_NUMBER_LENGTH)->check();
        if ($numberCheck === ValueChecker::IS_EMPTY) {
            $errorList['number'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($numberCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['number'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($number), self::PARALLEL_NUMBER_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createParallel($name, $number, $showInGroup);
            } else {
                $resDb = $manager->updateParallel($id, $name, $number, $showInGroup);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            preg_match('/SQLSTATE\[23000\].*main__parallel.main__parallel___unique_name/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['name'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$name],
                ];
            }
            preg_match('/SQLSTATE\[23000\].*main__parallel.main__parallel___unique_number/', $msg[0], $matches);
            if (!empty($matches)) {
                $errorList['number'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$number],
                ];
            }

            if (count($errorList) > 0) {
                $localLog->debug('client error message:', $errorList);
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeParallel($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::removeParallel');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        try {
            $manager = new Manager();
            $resDb = $manager->removeParallel($id);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('error:', $msg);
            preg_match('/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                    'args' => ['данные используются'],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getGroupList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getGroupList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getGroupList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['group_id'],
                'name' => $item['group_name'],
                'parallelName' => $item['parallel_name'],
                'canBeRemoved' => ($item['mug_count'] + $item['ml_count'] + $item['msgh_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getGroupById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getGroupById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getGroupById($groupId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Группа с id = {$groupId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['group_id'],
            'name' => $resDb[0]['group_name'],
            'parallelId' => $resDb[0]['parallel_id'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::saveGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $parallelId = $args['parallelId'];
        $teacherList = array_reduce($args['teacherList'], function ($carry, $item) {
            // ;
            $carry[] = [
                'userId' => $item,
            ];
            return $carry;
        });

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::GROUP_NAME_MAX_LENGTH)->check();

        $errorList = [];
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::GROUP_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createGroup($name, $parallelId);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateGroup($id, $name, $parallelId);
            }

            $manager->removeTeacherListFromGroup($id);
            if (!empty($teacherList)) {
                $manager->addTeacherListToGroup($id, $teacherList);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            preg_match('/SQLSTATE\[23000\].*main__group.main__group___unique_parallel_id_name/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$name],
                ];
            }

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::removeGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        try {
            $manager = new Manager();
            $manager->removeTeacherListFromGroup($id);
            $resDb = $manager->removeGroup($id);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('error:', $msg);
            preg_match('/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                    'args' => ['данные используются'],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getActiveTeacherList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getActiveTeacherList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getActiveTeacherList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getTeacherList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getTeacherList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getTeacherList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['teacher_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
                'roleStateId' => $item['role_state_id'],
                'canBeRemoved' => $item['mug_count'] === 0,
                'canBeBlocked' => $item['role_state_id'] === AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getTeacherListInGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getTeacherListInGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getTeacherListInGroup($groupId);

        $res = array_map(function ($item) {
            return [
                'id' => $item['teacher_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function blockTeacher($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::blockTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $action = $args['action'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();

        $roleStateId = ($action === 'block') ? AuthzConstant::ROLE_STATE_TEACHER_BLOCKED_ID : AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID;
        $resDb = $manager->blockTeacher($id, $roleStateId);

        return [Util::MakeSuccessOperationResult(), []];
    }


    public function getTeacherById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getTeacherById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $teacherId = $args['teacherId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getTeacherById($teacherId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Преподавателя с id = {$teacherId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['teacher_id'],
            'firstName' => $resDb[0]['first_name'],
            'lastName' => $resDb[0]['last_name'],
            'middleName' => $resDb[0]['middle_name'],
            'roleStateId' => $resDb[0]['role_state_id'],
            'login' => $resDb[0]['login'],
            'password' => '',
            'email' => $resDb[0]['email'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getGroupListForTeacher($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getGroupListForTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $teacherId = $args['teacherId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getGroupListForTeacher($teacherId);

        $res = array_map(function ($item) {
            return [
                'id' => $item['group_id'],
                'name' => $item['group_name'],
                'parallelName' => $item['parallel_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveTeacher($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::saveTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $login = $args['login'];
        $email = $args['email'];
        $password = $args['password'];
        $firstName = $args['firstName'];
        $lastName = $args['lastName'];
        $middleName = $args['middleName'];
        $roleStateId = $args['roleStateId'];
        $groupList = array_reduce($args['groupList'], function ($carry, $item) {
            // ;
            $carry[] = [
                'groupId' => $item,
            ];
            return $carry;
        });
        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        $loginCheck = (new ValueChecker($login))->notEmpty()->lengthLessOrEqual(self::LOGIN_MAX_LENGTH)->check();
        $emailCheck = (new ValueChecker($email))->notEmpty()->lengthLessOrEqual(self::EMAIL_MAX_LENGTH)->validEmail()->check();
        $passwordCheck = ($id === 0 || !(empty($password)))
            ? (new ValueChecker($password))->notEmpty()->lengthLessOrEqual(self::PASSWORD_MAX_LENGTH)->check()
            : (new ValueChecker($password))->check();
        $firstNameCheck = (new ValueChecker($firstName))->notEmpty()->lengthLessOrEqual(self::FIRST_NAME_MAX_LENGTH)->check();
        $lastNameCheck = (new ValueChecker($lastName))->notEmpty()->lengthLessOrEqual(self::LAST_NAME_MAX_LENGTH)->check();
        $middleNameCheck = (new ValueChecker($middleName))->lengthLessOrEqual(self::MIDDLE_NAME_MAX_LENGTH)->check();

        $errorList = [];
        if ($loginCheck === ValueChecker::IS_EMPTY) {
            $errorList['login'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($loginCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['login'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::FIRST_NAME_MAX_LENGTH],
            ];
        }
        if ($emailCheck === ValueChecker::IS_EMPTY) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($emailCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::FIRST_NAME_MAX_LENGTH],
            ];
        } else if ($emailCheck === ValueChecker::IS_NOT_VALID_EMAIL) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_EMAIL_INCORRECT,
                'args' => [$email],
            ];
        }
        if ($passwordCheck === ValueChecker::IS_EMPTY) {
            $errorList['password'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($passwordCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['password'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::FIRST_NAME_MAX_LENGTH],
            ];
        }
        if ($firstNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['firstName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($firstNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['firstName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::FIRST_NAME_MAX_LENGTH],
            ];
        }

        if ($lastNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['lastName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($lastNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['lastName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($lastName), self::LAST_NAME_MAX_LENGTH],
            ];
        }

        if ($middleNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['middleName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($middleNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['middleName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($middleName), self::MIDDLE_NAME_MAX_LENGTH],
            ];
        }

        $roleId = AuthzConstant::ROLE_TEACHER_ID;
        if (!AuthzConstant::CheckRoleStateId($roleId, $roleStateId)) {
            $errorList['roleStateId'] = [
                'code' => MWI18nHelper::MSG_FIELD_VALUE_IS_NOT_VALID,
                'args' => ["ROLE_TEACHER_ID", strval($roleStateId)],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {

            if (!empty($password)) {
                $secret = Setting::Get('app.account.passSecret');
                $password = Util::GenerateHash($password, "{$secret}");
            }

            $manager = new Manager();
            if ($id === 0) {
                $res = AuthzService::CreateAccount([
                    'roleId' => AuthzConstant::ROLE_TEACHER_ID,
                    'roleStateId' => $roleStateId,
                ]);
                $accountId = intval($res['accountId']);

                $resDb = $manager->createTeacher($accountId, $firstName, $lastName, $middleName, $login, $password, $email);
            } else {
                $resDb = $manager->updateTeacher($id, $firstName, $lastName, $middleName, $login, $password, $email);
            }

            $resDb = $manager->blockTeacher($id, $roleStateId);

            $manager->removeGroupListFromTeacher($id);
            if (!empty($groupList)) {
                $manager->addGroupListToTeacher($id, $groupList);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $errorList = [];
            preg_match('/SQLSTATE\[23000\].*main__user.main__user___unique_login/', $msg[0], $matches);
            if (!empty($matches)) {
                $errorList['login'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$login],
                ];
            }
            preg_match('/SQLSTATE\[23000\].*main__user.main__user___unique_email/', $msg[0], $matches);
            if (!empty($matches)) {
                $errorList['email'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$email],
                ];
            }

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeTeacher($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::removeTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        try {
            $manager = new Manager();

            $resDb = $manager->getTeacherById($id);
            if (count($resDb) !== 1) {
                MWException::ThrowEx(
                    errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                    logData: ['', "Преподавателя с id = {$id} не существует"],
                );
            }
            $accountId = $resDb[0]['account_id'];

            $resDb = $manager->removeTeacher($id);

            AuthzService::RemoveAccount(['accountId' => $accountId]);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('error:', $msg);
            preg_match('/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                    'args' => ['данные используются'],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getSubjectList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getSubjectList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getSubjectList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'canBeRemoved' => $item['ml_count'] === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getSubjectById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getSubjectById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $subjectId = $args['subjectId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getSubjectById($subjectId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Предмет с id = {$subjectId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
        ];

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveSubject($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::saveSubject');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::SUBJECT_NAME_MAX_LENGTH)->check();

        $errorList = [];
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::FIRST_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createSubject($name);
            } else {
                $resDb = $manager->updateSubject($id, $name);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $errorList = [];
            preg_match('/SQLSTATE\[23000\].*main__subject.main__subject___unique_name/', $msg[0], $matches);
            if (!empty($matches)) {
                $errorList['name'] = [
                    'code' => MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                    'args' => [$name],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeSubject($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::removeSubject');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        try {
            $manager = new Manager();
            $resDb = $manager->removeSubject($id);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('error:', $msg);
            preg_match('/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                    'args' => ['данные используются'],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getStudentList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getStudentList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getStudentList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['student_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
                'canBeRemoved' => ($item['msch_count'] + $item['msgh_count'] + $item['msl_count'] + $item['mss_count'] + $item['msst_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getStudentById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getStudentById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentId = $args['studentId'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getStudentById($studentId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Ученика с id = {$studentId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['student_id'],
            'firstName' => $resDb[0]['first_name'],
            'lastName' => $resDb[0]['last_name'],
            'middleName' => $resDb[0]['middle_name'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveStudent($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::saveStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $firstName = $args['firstName'];
        $lastName = $args['lastName'];
        $middleName = $args['middleName'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        $firstNameCheck = (new ValueChecker($firstName))->notEmpty()->lengthLessOrEqual(self::FIRST_NAME_MAX_LENGTH)->check();
        $lastNameCheck = (new ValueChecker($lastName))->notEmpty()->lengthLessOrEqual(self::LAST_NAME_MAX_LENGTH)->check();
        $middleNameCheck = (new ValueChecker($middleName))->lengthLessOrEqual(self::MIDDLE_NAME_MAX_LENGTH)->check();

        $errorList = [];
        if ($firstNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['firstName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($firstNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['firstName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::FIRST_NAME_MAX_LENGTH],
            ];
        }

        if ($lastNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['lastName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($lastNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['lastName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($lastName), self::LAST_NAME_MAX_LENGTH],
            ];
        }

        if ($middleNameCheck === ValueChecker::IS_EMPTY) {
            $errorList['middleName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($middleNameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['middleName'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($middleName), self::MIDDLE_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        $manager = new Manager();
        if ($id === 0) {
            $resDb = $manager->createStudent($firstName, $lastName, $middleName);
        } else {
            $resDb = $manager->updateStudent($id, $firstName, $lastName, $middleName);
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeStudent($args)
    {
        $localLog = Logger::Log()->withName('Module::Account::removeStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        try {
            $manager = new Manager();

            $resDb = $manager->removeStudent($id);

        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('error:', $msg);
            preg_match('/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/', $msg[0], $matches);
            $errorList = [];
            if (!empty($matches)) {
                $errorList['_msg_'] = [
                    'code' => MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                    'args' => ['данные используются'],
                ];
            }
            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }
}
