<?php

namespace MW\Module\Domain\SchoolYear;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{

    const SCHOOLYEAR_NAME_MAX_LENGTH = 100;
    const DATE_LENGTH = 10;

    public function getSchoolYearList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::SchoolYear::getSchoolYearList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getSchoolYearList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'startDate' => $item['start_date'],
                'finishDate' => $item['finish_date'],
                'isCurrent' => $item['is_current'] === 'Y',
                'canBeRemoved' => $item['mug_count'] === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getCurrentSchoolYearAndCount($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::SchoolYear::getCurrentSchoolYearAndCount');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getCurrentSchoolYearAndCount();

        $res = array_map(function ($item) {
            return [
                'currentId' => $item['current_id'],
                'count' => $item['count'],
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getSchoolYearById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::SchoolYear::getSchoolYearById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $schoolYearId = $args['schoolYearId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getSchoolYearById($schoolYearId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Учебный год с id = {$schoolYearId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
            'startDate' => $resDb[0]['start_date'],
            'finishDate' => $resDb[0]['finish_date'],
            'isCurrent' => $resDb[0]['is_current'] === 'Y',
        ];

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveSchoolYear($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::SchoolYear::saveSchoolYear');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $startDate = $args['startDate'];
        $finishDate = $args['finishDate'];
        $isCurrent = $args['isCurrent'] ? 'Y' : 'N';

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::SCHOOLYEAR_NAME_MAX_LENGTH)->check();
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::SCHOOLYEAR_NAME_MAX_LENGTH],
            ];
        }

        $startDateCheck = (new ValueChecker($startDate))->notEmpty()->lengthEqual(self::DATE_LENGTH)->check();
        if ($startDateCheck === ValueChecker::IS_EMPTY) {
            $errorList['startDate'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($startDateCheck === ValueChecker::LENGTH_IS_NOT_EQUAL) {
            $errorList['startDate'] = [
                'code' => MWI18nHelper::MSG_FIELD_VALUE_IS_NOT_VALID,
                'args' => ['startDate', $startDate],
            ];
        }

        $finishDateCheck = (new ValueChecker($finishDate))->notEmpty()->lengthEqual(self::DATE_LENGTH)->check();
        if ($finishDateCheck === ValueChecker::IS_EMPTY) {
            $errorList['finishDate'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($finishDateCheck === ValueChecker::LENGTH_IS_NOT_EQUAL) {
            $errorList['finishDate'] = [
                'code' => MWI18nHelper::MSG_FIELD_VALUE_IS_NOT_VALID,
                'args' => ['finishDate', $finishDate],
            ];
        }

        if (count($errorList) === 0) {
            $startDateCompFinishDateCheck = (new ValueChecker(strtotime($startDate)))->valueLess(strtotime($finishDate))->check();
            if ($startDateCompFinishDateCheck === ValueChecker::VALUE_GREAT) {
                $errorList['startDate'] = [
                    'code' => MWI18nHelper::MSG_FIELD_START_DATE_IS_GREAT_THAN_FINISH_DATE,
                    'args' => [$startDate, $finishDate],
                ];
            }
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getCurrentSchoolYearAndCount();

        $schoolYearCount = $resDb[0]['count'];
        $schoolYearCurrentId = $resDb[0]['current_id'];

        if ($schoolYearCount === 0) {
            $isCurrent = 'Y';
        } else {
            if ($schoolYearCurrentId === $id) {
                $isCurrent = 'Y';
            } else {
                if ($isCurrent === 'Y') {
                    $manager->updateSchoolYearIsCurrent($schoolYearCurrentId, 'N');
                }
            }
        }

        if ($id === 0) {
            $resDb = $manager->createSchoolYear($name, $startDate, $finishDate, $isCurrent);
        } else {
            $resDb = $manager->updateSchoolYear($id, $name, $startDate, $finishDate, $isCurrent);
        }

        return [Util::MakeSuccessOperationResult(), []];
    }

    public function removeSchoolYear($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::SchoolYear::removeSchoolYear');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeSchoolYear($id);
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
