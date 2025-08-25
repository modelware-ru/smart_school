<?php

namespace MW\Module\Domain\Parallel;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;


class Main
{
    const PARALLEL_NAME_MAX_LENGTH = 100;
    const PARALLEL_NUMBER_LENGTH = 10;
    const PARALLEL_ORDER_LENGTH = 3;

    public function getParallelList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Parallel::getParallelList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getParallelList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'number' => $item['number'],
                'showInGroup' => $item['show_in_group'] === 'Y',
                'order' => $item['order'],
                'canBeRemoved' => ($item['mg_count'] + $item['msch_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getParallelById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Parallel::getParallelById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $parallelId = $args['parallelId'];

        $errorList = [];

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
            'order' => $resDb[0]['order'],
        ];

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveParallel($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::Parallel::saveParallel');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $number = $args['number'];
        $showInGroup = $args['showInGroup'] ? 'Y' : 'N';
        $order = $args['order'];

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::PARALLEL_NAME_MAX_LENGTH)->check();
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

        $orderCheck = (new ValueChecker($order))->lengthLessOrEqual(self::PARALLEL_ORDER_LENGTH)->check();
        if ($orderCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['order'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($order), self::PARALLEL_ORDER_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createParallel($name, $number, $showInGroup, $order);
            } else {
                $resDb = $manager->updateParallel($id, $name, $number, $showInGroup, $order);
            }
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__parallel___unique_name/',
                $msg[0],
                'name',
                MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                [$name]
            );

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__parallel___unique_number/',
                $msg[0],
                'number',
                MWI18nHelper::MSG_IMPOSSIBLE_TO_REMOVE_DATA,
                [$number]
            );

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
        $localLog = Logger::Log()->withName('Module::Domain::Parallel::removeParallel');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeParallel($id);
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
