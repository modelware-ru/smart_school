<?php

namespace MW\Module\Domain\Teacher;

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
    const LOGIN_MAX_LENGTH = 50;
    const EMAIL_MAX_LENGTH = 100;
    const PASSWORD_MAX_LENGTH = 20;
    const FIRST_NAME_MAX_LENGTH = 100;
    const LAST_NAME_MAX_LENGTH = 100;
    const MIDDLE_NAME_MAX_LENGTH = 100;

    public function getActiveTeacherList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getActiveTeacherList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

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
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getTeacherList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

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
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getTeacherListInGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];

        $errorList = [];

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
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::blockTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $action = $args['action'];

        $errorList = [];

        $manager = new Manager();

        $roleStateId = ($action === 'block') ? AuthzConstant::ROLE_STATE_TEACHER_BLOCKED_ID : AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID;
        $resDb = $manager->blockTeacher($id, $roleStateId);

        return [Util::MakeSuccessOperationResult(), []];
    }


    public function getTeacherById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getTeacherById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $teacherId = $args['teacherId'];

        $errorList = [];

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
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getGroupListForTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $teacherId = $args['teacherId'];
        $schoolYearId = $args['schoolYearId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getGroupListForTeacher($teacherId, $schoolYearId);

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
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::saveTeacher');
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

        $errorList = [];

        // check. start
        $loginCheck = (new ValueChecker($login))->notEmpty()->lengthLessOrEqual(self::LOGIN_MAX_LENGTH)->check();
        if ($loginCheck === ValueChecker::IS_EMPTY) {
            $errorList['login'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($loginCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['login'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::LOGIN_MAX_LENGTH],
            ];
        }

        $emailCheck = (new ValueChecker($email))->notEmpty()->lengthLessOrEqual(self::EMAIL_MAX_LENGTH)->validEmail()->check();
        if ($emailCheck === ValueChecker::IS_EMPTY) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($emailCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::EMAIL_MAX_LENGTH],
            ];
        } else if ($emailCheck === ValueChecker::IS_NOT_VALID_EMAIL) {
            $errorList['email'] = [
                'code' => MWI18nHelper::MSG_FIELD_EMAIL_INCORRECT,
                'args' => [$email],
            ];
        }

        $passwordCheck = ($id === 0 || !(empty($password)))
            ? (new ValueChecker($password))->notEmpty()->lengthLessOrEqual(self::PASSWORD_MAX_LENGTH)->check()
            : (new ValueChecker($password))->check();
        if ($passwordCheck === ValueChecker::IS_EMPTY) {
            $errorList['password'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($passwordCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['password'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($firstName), self::PASSWORD_MAX_LENGTH],
            ];
        }

        $firstNameCheck = (new ValueChecker($firstName))->notEmpty()->lengthLessOrEqual(self::FIRST_NAME_MAX_LENGTH)->check();
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

        $lastNameCheck = (new ValueChecker($lastName))->notEmpty()->lengthLessOrEqual(self::LAST_NAME_MAX_LENGTH)->check();
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

        $middleNameCheck = (new ValueChecker($middleName))->lengthLessOrEqual(self::MIDDLE_NAME_MAX_LENGTH)->check();
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
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateTeacher($id, $firstName, $lastName, $middleName, $login, $password, $email);
            }

            $resDb = $manager->blockTeacher($id, $roleStateId);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__user___unique_login/',
                $msg[0],
                'login',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                [$login]
            ); 

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__user___unique_email/',
                $msg[0],
                'email',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                [$email]
            ); 

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeTeacher($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::removeTeacher');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

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
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/',
                $msg[0],
                '_msg_',
                MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                ['данные используются']
            );             

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getTeacherGroupBySchoolYearId($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::getTeacherGroupBySchoolYearId');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $schoolYearId = $args['schoolYearId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTeacherGroupBySchoolYearId($schoolYearId);

        $res = array_map(function ($item) {
            return [
                'parallelId' => $item['parallel_id'],
                'parallelName' => $item['parallel_name'],
                'groupId' => $item['group_id'],
                'groupName' => $item['group_name'],
                'userId' => $item['user_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveTeacherGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Teacher::saveTeacherGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];
        $schoolYearId = $args['schoolYearId'];
        $teacherList = $args['teacherList'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->removeTeacherGroup($groupId, $schoolYearId);
        if (count($teacherList) > 0) {
            $resDb = $manager->createTeacherGroup($groupId, $schoolYearId, $teacherList);
        }

        return [Util::MakeSuccessOperationResult(), []];
    }
}
