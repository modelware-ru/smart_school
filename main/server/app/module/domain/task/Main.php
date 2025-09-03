<?php

namespace MW\Module\Domain\Task;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const TASK_NAME_MAX_LENGTH = 100;

    public function getTaskList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Task::getTaskList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTaskList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'canBeRemoved' => $item['mst_count'] === 0,
            ];
        }, $resDb);

        foreach ($res as $key => $item) {
            $taskId = $item['id'];
            $resDb = $manager->getTopicSubtopicListByTaskId($taskId);
            $topicSubtopicList = array_map(function ($item) {
                return [
                    'topicId' => $item['topic_id'],
                    'topicName' => $item['topic_name'],
                    'subtopicId' => $item['subtopic_id'],
                    'subtopicName' => $item['subtopic_name'],
                ];
            }, $resDb);

            $res[$key]['topicSubtopicList'] = $topicSubtopicList;
        }

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getTaskById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Task::getTaskById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $taskId = $args['taskId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTaskById($taskId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Задача с id = {$taskId} не существует"],
            );
        }

        $taskName = $resDb[0]['name'];

        $resDb = $manager->getTopicSubtopicListByTaskId($taskId);
        $topicSubtopicList = array_map(function ($item) {
            return [
                'topicId' => $item['topic_id'],
                'topicName' => $item['topic_name'],
                'subtopicId' => $item['subtopic_id'],
                'subtopicName' => $item['subtopic_name'],
            ];
        }, $resDb);


        $res =  [
            'id' => $taskId,
            'name' => $taskName,
            'topicSubtopicList' => $topicSubtopicList,
        ];

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveTask($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Task::saveTask');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $subtopicList = $args['subtopicList'];

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::TASK_NAME_MAX_LENGTH)->check();
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::TASK_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createTask($name);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateTask($id, $name);
                $resDb = $manager->removeTaskSubtopicList($id);
            }

            if (count($subtopicList) > 0) {
                $newSubtopicList = array_map(function ($item) {
                    return [
                        'subtopicId' => $item,
                    ];
                }, $subtopicList);

                $resDb = $manager->addSubtopicListToTask($newSubtopicList, $id);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__task___unique_name/',
                $msg[0],
                'name',
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

    public function removeTask($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Task::removeTask');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeTask($id);
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
