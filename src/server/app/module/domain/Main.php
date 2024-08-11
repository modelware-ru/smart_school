<?php

namespace MW\Module\Domain;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{
    const PARALLEL_NAME_MAX_LENGTH = 100;
    const PARALLEL_NUMBER_LENGTH = 10;
    const GROUP_NAME_MAX_LENGTH = 100;

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
                'id' => $item['parallel_id'],
                'name' => $item['name'],
                'number' => $item['number'],
                'showInGroup' => $item['show_in_group'] === 'Y',
                'canBeDeleted' => ($item['mg_count'] + $item['msch_count']) === 0,
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
                'canBeDeleted' => ($item['mug_count'] + $item['ml_count'] + $item['msgh_count']) === 0,
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
            } else {
                $resDb = $manager->updateGroup($id, $name, $parallelId);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            preg_match('/SQLSTATE\[23000\].*main__group.main__group___unique_name/', $msg[0], $matches);
            $errorList = [];
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
}
