<?php

namespace MW\Module\Domain\Student;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const FIRST_NAME_MAX_LENGTH = 100;
    const LAST_NAME_MAX_LENGTH = 100;
    const MIDDLE_NAME_MAX_LENGTH = 100;

    public function getStudentList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getStudentList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['student_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
                'classNumber' => $item['class_number'],
                'classLetter' => $item['class_letter'],
                'classParallelId' => $item['class_parallel_id'],
                'groupName' => $item['group_name'],
                'groupParallelId' => $item['group_parallel_id'],
                'groupParallelNumber' => $item['group_parallel_number'],
                'canBeRemoved' => ($item['msch_count'] + $item['msgh_count'] + $item['msl_count'] + $item['mss_count']) === 0,
                'canBeShowHistory' => ($item['msch_count'] + $item['msgh_count']) > 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getStudentById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentId = $args['studentId'];

        $errorList = [];

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

    public function getStudentByIdList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentByIdList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentIdList = $args['studentIdList'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getStudentByIdList($studentIdList);

        if (count($resDb) === 0) {
            $logVar = implode(',', $studentIdList);
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Учеников с id = ({$logVar}) не существует"],
            );
        }

        $res = array_map(function ($item) {
            return [
                'id' => $item['student_id'],
                'firstName' => $item['first_name'],
                'lastName' => $item['last_name'],
                'middleName' => $item['middle_name'],
                'classNumber' => $item['class_number'],
                'classLetter' => $item['class_letter'],
                'classParallelId' => $item['class_parallel_id'],
            ];
        }, $resDb);
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveStudent($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::saveStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $firstName = $args['firstName'];
        $lastName = $args['lastName'];
        $middleName = $args['middleName'];

        $errorList = [];

        // check. start
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
        $localLog = Logger::Log()->withName('Module::Domain::Student::removeStudent');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();

            $resDb = $manager->removeStudent($id);
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

    public function changeClass($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::changeClass');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $startDate = $args['startDate'];
        $classLetter = $args['classLetter'];
        $parallelId = $args['parallelId'];
        $reason = $args['reason'];
        $studentIdList = $args['studentIdList'];

        $errorList = [];

        $manager = new Manager();

        $resDb =  $manager->getMaxOrderForStudentClassHistory($studentIdList, $startDate);

        $studentIdList = array_map(function ($item) use ($resDb) {
            $res = NULL;
            foreach ($resDb as $key => $value) {
                if ($value['student_id'] === $item['id']) {
                    $res = $value['max_order'];
                    break;
                }
            }
            return [
                'id' => $item['id'],
                'order' => is_null($res) ? 1 : $res + 1,
            ];
        }, $studentIdList);

        $resDb = $manager->addStudentClassHistory($studentIdList, $startDate, $parallelId, $classLetter, $reason);

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function changeGroup($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::changeGroup');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $startDate = $args['startDate'];
        $groupId = $args['groupId'];
        $reason = $args['reason'];
        $studentIdList = $args['studentIdList'];

        $errorList = [];

        $manager = new Manager();

        $sl = array_reduce($studentIdList, function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        }, []);

        $resDb = $manager->getStudentByIdList($sl);

        if (count($resDb) === 0) {
            $logVar = implode(',', $sl);
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Учеников с id = ({$logVar}) не существует"],
            );
        }

        $classParallelList = array_reduce($resDb, function ($carry, $item) {
            if (!is_null($item['class_parallel_id']) && !in_array($item['class_parallel_id'], $carry)) {
                $carry[] = $item['class_parallel_id'];
            }

            return $carry;
        }, []);

        if (count($classParallelList) !== 1) {
            $errorList['_msg_'] = [
                'code' => MWI18nHelper::MSG_FIELD_VALUE_IS_NOT_VALID,
                'args' => ['classParallelList', 'не единственный'],
            ];
            $localLog->debug('client error message:', $errorList);
            return [Util::MakeFailOperationResult($errorList), []];
        }

        $resDb =  $manager->getMaxOrderForStudentGroupHistory($studentIdList, $startDate);

        $studentIdList = array_map(function ($item) use ($resDb) {
            $res = NULL;
            foreach ($resDb as $key => $value) {
                if ($value['student_id'] === $item['id']) {
                    $res = $value['max_order'];
                    break;
                }
            }
            return [
                'id' => $item['id'],
                'order' => is_null($res) ? 1 : $res + 1,
            ];
        }, $studentIdList);

        $resDb = $manager->addStudentGroupHistory($studentIdList, $startDate, $groupId, $reason);

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function getStudentClassGroupHistory($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentClassGroupHistory');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentId = $args['studentId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getStudentClassGroupHistory($studentId);

        $res = array_map(function ($item) {
            return [
                'classHistoryId' => $item['class_history_id'],
                'groupHistoryId' => $item['group_history_id'],
                'startDate' => substr($item['start_date'], 0, 10),
                'order' => $item['order'],
                'className' => $item['class_name'],
                'groupName' => $item['group_name'],
                'reason' => $item['reason'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getStudentSerieById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentSerieById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentSerieId = $args['studentSerieId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getStudentSerieById($studentSerieId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Серии для ученика с id = {$studentSerieId} не существует"],
            );
        }

        $res =  [
            'serieType' => $resDb[0]['serie_type'],
            'serieDate' => substr($resDb[0]['serie_date'], 0, 10),
            'serieId' => $resDb[0]['serie_id'],
            'studentId' => $resDb[0]['student_id'],
            'firstName' => $resDb[0]['first_name'],
            'lastName' => $resDb[0]['last_name'],
            'middleName' => $resDb[0]['middle_name'],
            'serieName' => $resDb[0]['serie_name'],
            'lessonDate' => substr($resDb[0]['lesson_date'], 0, 10),
            'subjectName' => $resDb[0]['subject_name'],
            'groupName' => $resDb[0]['group_name'],
            'parallelName' => $resDb[0]['parallel_name'],
            'parallelNumber' => $resDb[0]['parallel_number'],
        ];
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getStudentSolutionById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::getStudentSolutionById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $studentSerieId = $args['studentSerieId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getStudentSolutionById($studentSerieId);

        if (count($resDb) === 0) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Серии для ученика с id = {$studentSerieId} не существует"],
            );
        }

        $res = array_map(function ($item) {
            return [
                'serieTaskId' => $item['serie_task_id'],
                'solutionId' => is_null($item['solution_id']) ? 0 : $item['solution_id'],
                'solutionValue' => is_null($item['solution_value']) ? "" : $item['solution_value'],
                'solutionDate' => is_null($item['solution_date']) ? "-" : substr($item['solution_date'], 0, 10),
                'taskName' => $item['task_name'],
            ];
        }, $resDb);
        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveStudentSolution($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Student::saveStudentSolution');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $taskList = $args['taskList'];
        $studentSerieId = $args['studentSerieId'];

        $errorList = [];

        // removeStudentSolution
        $solutionListToRemove = array_reduce($taskList, function ($carry, $item) {
            if (empty($item['value']) && $item['solutionId'] !== 0) {
                $carry[] = [
                    'solutionId' => $item['solutionId'],
                ];
            }
            return $carry;
        }, []);

        $manager = new Manager();

        if (count($solutionListToRemove) > 0) {
            $manager->removeStudentSolution($solutionListToRemove);
        }

        // updateStudentSolution
        $solutionListToUpdate = array_reduce($taskList, function ($carry, $item) {
            if (!empty($item['value']) && $item['solutionId'] !== 0) {
                $carry[] = [
                    'solutionId' => $item['solutionId'],
                    'value' => $item['value'],
                ];
            }
            return $carry;
        }, []);

        $now = date('Y-m-d', time());
        if (count($solutionListToUpdate) > 0) {
            $manager->updateStudentSolution($solutionListToUpdate, $now);
        }

        // createStudentSolution
        $solutionListToCreate = array_reduce($taskList, function ($carry, $item) {
            if (!empty($item['value']) && $item['solutionId'] === 0) {
                $carry[] = [
                    'serieTaskId' => $item['serieTaskId'],
                    'value' => $item['value'],
                ];
            }
            return $carry;
        }, []);

        if (count($solutionListToCreate) > 0) {
            $manager->createStudentSolution($studentSerieId, $solutionListToCreate, $now);
        }

        return [Util::MakeSuccessOperationResult(), []];
    }
}
