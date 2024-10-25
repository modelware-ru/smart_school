<?php

namespace MW\Module\Domain\Lesson;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const DATE_LENGTH = 10;

    public function getLessonListForGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::getLessonListForGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $groupId = $args['groupId'];
        $startDate = $args['startDate'];
        $finishDate = $args['finishDate'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getLessonListForGroup($groupId, $startDate, $finishDate);

        $res = array_map(function ($item) {
            return [
                'id' => $item['lesson_id'],
                'date' => substr($item['lesson_date'], 0, 10),
                'subjectId' => $item['subject_id'],
                'subjectName' => $item['subject_name'],
                'canBeRemoved' => ($item['msl_count'] + $item['mls_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getLessonById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::getLessonById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getLessonById($lessonId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Занятие с id = {$lessonId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['lesson_id'],
            'date' => substr($resDb[0]['lesson_date'], 0, 10),
            'parallelId' => $resDb[0]['parallel_id'],
            'groupId' => $resDb[0]['group_id'],
            'groupName' => $resDb[0]['group_name'],
            'subjectId' => $resDb[0]['subject_id'],
            'subjectName' => $resDb[0]['subject_name'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::saveLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $date = $args['date'];
        $subjectId = $args['subjectId'];
        $groupId = $args['groupId'];
        $serieList = array_reduce($args['serieList'], function ($carry, $item) {
            // ;
            $carry[] = [
                'serieId' => $item,
            ];
            return $carry;
        });

        $errorList = [];

        // check. start
        $dateCheck = (new ValueChecker($date))->notEmpty()->lengthEqual(self::DATE_LENGTH)->check();
        if ($dateCheck === ValueChecker::IS_EMPTY) {
            $errorList['date'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($dateCheck === ValueChecker::LENGTH_IS_NOT_EQUAL) {
            $errorList['date'] = [
                'code' => MWI18nHelper::MSG_FIELD_VALUE_IS_NOT_VALID,
                'args' => ['date', $date],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        $manager = new Manager();
        if ($id === 0) {
            $resDb = $manager->createLesson($date, $subjectId, $groupId);
            $id = $resDb[0];
        } else {
            $resDb = $manager->updateLesson($id, $date, $subjectId, $groupId);
        }

        $manager->removeSerieListFromLesson($id);
        if (!empty($serieList)) {
            $manager->addSerieListToLesson($id, $serieList);
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::removeLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $manager->removeSerieListFromLesson($id);
            $resDb = $manager->removeLesson($id);
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

    public function getStudentListForLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::getStudentListForLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getLessonById($lessonId);
        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Занятие с id = {$lessonId} не существует"],
            );
        }

        $lessonDate = substr($resDb[0]['lesson_date'], 0, 10);
        $parallelId = $resDb[0]['parallel_id'];
        $groupId = $resDb[0]['group_id'];

        $resDb = $manager->getStudentListForLesson($lessonId, $lessonDate, $parallelId, $groupId);

        $studentList = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
                'note' => $item['note'],
                'attendanceDictId' => $item['attendanceDict_id'],
            ];
        }, $resDb);

        foreach ($studentList as $index => $student) {
            $studentId = $student['id'];
            $resDb = $manager->getStudentSerieForLesson($studentId, $lessonId);

            $studentList[$index]['serieList'] = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'serieId' => $item['serie_id'],
                    'serieType' => $item['serie_type'],
                    'serieName' => $item['serie_name'],
                    'hasSolution' => $item['has_solution'] === 1,
                ];
            }, $resDb);
        }

        return [Util::MakeSuccessOperationResult($studentList), []];
    }

    public function getAttendanceDict($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::getAttendanceDict');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getAttendanceDict();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'display' => $item['display'],
                'default' => $item['default'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function addSerieToLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::addSerieToLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];
        $serieId = $args['serieId'];
        $studentClassList = $args['studentClassList'];
        $studentHomeList = $args['studentHomeList'];

        $errorList = [];
        // check. start
        $intersect = array_intersect($studentClassList, $studentHomeList);
        if (count($intersect) !== 0) {
            $errorList['_msg_'] = [
                'code' => MWI18nHelper::MSG_WRONG_SERIE_TYPE,
                'args' => [implode(',', $intersect)],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        $studentClassListEx = array_map(function ($item) {
            return [
                'type' => 'CLASS',
                'studentId' => $item,
            ];
        }, $studentClassList);

        $studentHomeListEx = array_map(function ($item) {
            return [
                'type' => 'HOME',
                'studentId' => $item,
            ];
        }, $studentHomeList);

        $studentList = array_merge($studentClassListEx, $studentHomeListEx);

        $manager = new Manager();
        $now = date('Y-m-d', time());
        $manager->addSerieToLesson($lessonId, $now, $serieId, $studentList);

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeSerieFromLesson($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Lesson::removeSerieFromLesson');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $lessonId = $args['lessonId'];
        $serieId = $args['serieId'];
        $studentClassList = $args['studentClassList'];
        $studentHomeList = $args['studentHomeList'];

        $errorList = [];
        $studentClassListEx = array_map(function ($item) {
            return [
                'type' => 'CLASS',
                'studentId' => $item,
            ];
        }, $studentClassList);

        $studentHomeListEx = array_map(function ($item) {
            return [
                'type' => 'HOME',
                'studentId' => $item,
            ];
        }, $studentHomeList);

        $studentList = array_merge($studentClassListEx, $studentHomeListEx);

        $manager = new Manager();
        $manager->removeSerieFromLesson($lessonId, $serieId, $studentList);

        return [Util::MakeSuccessOperationResult(), []];
    }
}
