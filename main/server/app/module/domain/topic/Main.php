<?php

namespace MW\Module\Domain\Topic;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const TOPIC_NAME_MAX_LENGTH = 100;

    public function getTopicList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Topic::getTopicList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTopicList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'canBeRemoved' => $item['mts_count'] === 0 && $item['ms_count'] === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getTopicSubtopicList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Topic::getTopicSubtopicList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTopicList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'subtopicList' => [],
            ];
        }, $resDb);

        foreach ($res as $key => $item) {
            $topicId = $item['id'];
            $resDb = $manager->getSubtopicListById($topicId);
            $subtopicList = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                ];
            }, $resDb);

            $res[$key]['subtopicList'] = $subtopicList;
        }

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getTopicById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Topic::getTopicById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $topicId = $args['topicId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getTopicById($topicId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Тема с id = {$topicId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
        ];

        $resDb = $manager->getSubtopicListById($topicId);
        $subtopicList = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }, $resDb);

        $res['subtopicList'] = $subtopicList;

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveTopic($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Topic::saveTopic');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $removedSubtopicIdList = $args['removedSubtopicIdList'];
        $newSubtopicList = array_unique($args['newSubtopicList']);

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::TOPIC_NAME_MAX_LENGTH)->check();
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::TOPIC_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createTopic($name);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateTopic($id, $name);
                if (!empty($removedSubtopicIdList)) {
                    $resDb = $manager->removeSubtopicListFromTopic($removedSubtopicIdList, $id);
                }
            }

            if (count($newSubtopicList) > 0) {
                $newSubtopicNameList = array_map(function ($item) {
                    return [
                        'name' => $item,
                    ];
                }, $newSubtopicList);

                $resDb = $manager->addSubtopicListToTopic($newSubtopicNameList, $id);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__topic___unique_name/',
                $msg[0],
                'name',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                [$name]
            );

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__subtopic___unique_topic_id_name/',
                $msg[0],
                'subtopicList',
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

    public function removeTopic($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Topic::removeTopic');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeTopic($id);
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
