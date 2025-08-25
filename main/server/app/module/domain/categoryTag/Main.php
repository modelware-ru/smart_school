<?php

namespace MW\Module\Domain\CategoryTag;

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\MWException;
use MW\Shared\ValueChecker;

class Main
{
    const CATEGORYTAG_NAME_MAX_LENGTH = 100;

    public function getCategoryTagList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::CategoryTag::getCategoryTagList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getCategoryTagList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'canBeRemoved' => $item['mt_count'] === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function getCategoryTagById($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::CategoryTag::getCategoryTagById');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $categoryTagId = $args['categoryTagId'];

        $errorList = [];

        $manager = new Manager();
        $resDb = $manager->getCategoryById($categoryTagId);

        if (count($resDb) !== 1) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
                logData: ['', "Категория тегов с id = {$categoryTagId} не существует"],
            );
        }

        $res =  [
            'id' => $resDb[0]['id'],
            'name' => $resDb[0]['name'],
        ];

        $resDb = $manager->getCategoryTagListById($categoryTagId);
        $tagList = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }, $resDb);

        $res['tagList'] = $tagList;

        return [Util::MakeSuccessOperationResult($res), []];
    }

    public function saveCategoryTag($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::CategoryTag::saveCategoryTag');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];
        $name = $args['name'];
        $removedTagIdList = $args['removedTagIdList'];
        $newTagList = array_unique($args['newTagList']);

        $errorList = [];

        // check. start
        $nameCheck = (new ValueChecker($name))->notEmpty()->lengthLessOrEqual(self::CATEGORYTAG_NAME_MAX_LENGTH)->check();
        if ($nameCheck === ValueChecker::IS_EMPTY) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_REQUIRED,
                'args' => [],
            ];
        } else if ($nameCheck === ValueChecker::LENGTH_IS_NOT_LESS_OR_EQUAL) {
            $errorList['name'] = [
                'code' => MWI18nHelper::MSG_FIELD_IS_TOO_LONG,
                'args' => [strlen($name), self::CATEGORYTAG_NAME_MAX_LENGTH],
            ];
        }

        if (count($errorList) > 0) {
            return [Util::MakeFailOperationResult($errorList), []];
        }
        // check. finish

        try {
            $manager = new Manager();
            if ($id === 0) {
                $resDb = $manager->createCategoryTag($name);
                $id = $resDb[0];
            } else {
                $resDb = $manager->updateCategoryTag($id, $name);
                if (!empty($removedTagIdList)) {
                    $resDb = $manager->removeTagListFromCategoryTag($removedTagIdList, $id);
                }
            }

            $resDb = $manager->addTagListToCategoryTag($newTagList, $id);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__categoryTag___unique_name/',
                $msg[0],
                'name',
                MWI18nHelper::MSG_FIELD_WITH_DUPLICATED_VALUE,
                [$name]
            );

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\].*main__tag___unique_name_categoryTag_id/',
                $msg[0],
                'tagList',
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

    public function removeCategoryTag($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::CategoryTag::removeCategoryTag');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];
        $id = $args['id'];

        $errorList = [];

        try {
            $manager = new Manager();
            $resDb = $manager->removeCategoryTag($id);
        } catch (MWException $e) {
            $msg = $e->logData();
            $localLog->error('Error:', $msg);

            Util::SQLConstraintHandler(
                $errorList,
                '/SQLSTATE\[23000\]: Integrity constraint violation: 1451 Cannot delete or update a parent row:.*/',
                $msg[0],
                'name',
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
