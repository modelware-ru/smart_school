<?php

namespace MW\Module\Domain\Serie;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const SERIE_NAME_MAX_LENGTH = 100;

    public function getSerieList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::getSerieList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getSerieList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'canBeRemoved' => ($item['mst_count'] + $item['mss_count'] + $item['mls_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getSerieListInLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::getSerieListInLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getSerieListInLesson($lessonId);

        $res = array_map(function ($item) {
            return [
                'id' => $item['serie_id'],
                'name' => $item['serie_name'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }


    public function getSerieById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::getSerieById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $serieId = $args['serieId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getSerieById($serieId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Серии с id = {$serieId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
        ];

        $resDb = $manager->getSerieTaskListById($serieId);
        $taskList = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }, $resDb);

        $res['taskList'] = $taskList;

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveSerie($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::saveSerie');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $removedTaskIdList = $args['removedTaskIdList'];
        $newTaskList = array_unique($args['newTaskList']);

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::SERIE_NAME_MAX_LENGTH)->check();
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::SERIE_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createSerie($name);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateSerie($id, $name);
                if (!empty($removedTaskIdList)) {
                    $resDb = $manager->removeTaskListFromSerie($removedTaskIdList, $id);
                }
            }

            if (count($newTaskList) > 0) {
                $resDb = $manager->fetchTaskList($newTaskList);
                $existedTaskIdList = array_map(function ($item) {
                    return [
                        'taskId' => strval($item['id']),
                    ];
                }, $resDb);

                $resDb = $manager->createTaskList($newTaskList);
                $newTaskIdList = array_map(function ($item) {
                    return [
                        'taskId' => $item,
                    ];
                }, $resDb);

                $resultTaskList = array_merge($existedTaskIdList, $newTaskIdList);
                $resDb = $manager->addTaskListToSerie($resultTaskList, $id);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__serie___unique_name/',
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

    public function removeSerie($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::removeSerie');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeSerie($id);
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


    public function addHomeSerieToStudent($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::addHomeSerieToStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentId = $args['studentId'];
        $serieId = $args['serieId'];
        $groupId = $args['groupId'];
        $date = $args['date'];

        // check. start
        // check. finish

        try {
            $manager = new Manager();
            $manager->addHomeSerieToStudent($studentId, $serieId, $groupId, $date);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\]: Integrity constraint violation: 1062 Duplicate entry.*/',
                $msg[0],
                '_msg_',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                ['данные дублируются']
            );

            if (count($errorList) > 0) {
                return [Util::MakeFailOperationResult($errorList), []];
            }
            throw $e;
        }
        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeHomeSerieFromStudent($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Serie::removeHomeSerieFromStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentSerieId = $args['id'];

        $manager = new Manager();
        $manager->removeHomeSerieFromStudent($studentSerieId);

        return [Util::MakeSuccessOperationResult(), []];
    }
}
