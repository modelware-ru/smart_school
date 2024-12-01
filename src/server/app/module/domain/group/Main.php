<?php

namespace MW\Module\Domain\Group;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{
    const GROUP_NAME_MAX_LENGTH = 100;
    const GROUP_ORDER_MAX_LENGTH = 3;

    public function getGroupList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::getGroupList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getGroupList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['group_id'],
                'name' => $item['group_name'],
                'parallelId' => $item['parallel_id'],
                'parallelName' => $item['parallel_name'],
                'canBeRemoved' => ($item['mug_count'] + $item['ml_count'] + $item['msgh_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getGroupById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::getGroupById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];

        $errorList = [];

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
            'order' => $resDb[0]['group_order'],
            'parallelId' => $resDb[0]['parallel_id'],
            'parallelNumber' => $resDb[0]['parallel_number'],
            'parallelName' => $resDb[0]['parallel_name'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getGroupByLessonId($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::getGroupByLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getGroupByLessonId($lessonId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Группа для занятия с id = {$lessonId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['group_id'],
            'name' => $resDb[0]['group_name'],
            'order' => $resDb[0]['group_order'],
            'parallelId' => $resDb[0]['parallel_id'],
            'parallelName' => $resDb[0]['parallel_name'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getGroupListByParallelId($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::getGroupListByParallelId');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $parallelId = $args['parallelId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getGroupListByParallelId($parallelId);

        $res = array_map(function ($item) {
            return [
                'id' => $item['group_id'],
                'name' => $item['group_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::saveGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $parallelId = $args['parallelId'];
        $order = $args['order'];

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::GROUP_NAME_MAX_LENGTH)->check();
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

        $orderCheck = (new ValueChecker($order))->lengthLessOrEqual(self::GROUP_ORDER_MAX_LENGTH)->check();
        if ($orderCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['order'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($order), self::GROUP_ORDER_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createGroup($name, $parallelId, $order);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateGroup($id, $name, $parallelId, $order);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__group___unique_parallel_id_name/',
                $msg[0],
                '_msg_',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                [$name]
            );

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Group::removeGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeTeacherListFromGroup($id);

            $resDb = $manager->removeGroup($id);
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
}
