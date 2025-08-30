<?php

namespace MW\App;

use MW\Service\Authz\Constant as AuthzConstant;
use MW\Service\Authz\Main as AuthzService;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;

class Page
{
    public static function Init($resource, $title)
    {
        global $templateData;
        global $langId;
        global $accountId;
        global $roleId;
        global $roleStateId;

        $roleName = AuthzConstant::GetRoleName($roleId);

        try {
            $log = Logger::Init("page__{$resource}_{$roleName}");
            $log->notice('start');

            // TEST
            if ($resource === "test") {
                $templateData = [
                    'title' => MWI18nHelper::PAGE_TITLE_TEACHER,
                ];
                echo Util::RenderTemplate("app/template/test.php");
                return;
            }

            $resourceType = AuthzConstant::RESOURCE_TYPE_PAGE;
            $actionId = AuthzConstant::ACTION_PAGE_SHOW;
            $pagePermissionList = AuthzService::GetPermissionList([
                'accountId' => $accountId,
                'roleId' => $roleId,
                'roleStateId' => $roleStateId,
                'resourceType' => $resourceType,
                'resource' => $resource,
                'actionId' => $actionId,
            ]);

            $pagePermission = count($pagePermissionList) >= 1 ? $pagePermissionList[0]['permission'] : (Setting::Get('authz.defaultPolicy'))[$resourceType];

            if ($pagePermission !== AuthzConstant::PERMISSION_ALLOW) {
                MWException::ThrowEx(
                    errCode: MWI18nHelper::ERR_AUTHORIZATION_NEEDED,
                    logData: [$roleId, $resource, $actionId],
                );
            }

            $widgetPermissionList = AuthzService::GetPermissionList([
                'accountId' => $accountId,
                'roleId' => $roleId,
                'roleStateId' => $roleStateId,
                'resource' => null,
                'resourceType' => AuthzConstant::RESOURCE_TYPE_WIDGET,
                'actionId' => AuthzConstant::ACTION_WIDGET_SHOW,
            ]);

            $widgetPermission = array_reduce($widgetPermissionList, function ($carry, $item) {

                if (!array_key_exists($item['resource'], $carry)) {
                    $carry[$item['resource']] = [];
                }

                if (!array_key_exists($item['actionId'], $carry[$item['resource']])) {
                    $carry[$item['resource']][$item['actionId']] = [];
                }

                $carry[$item['resource']][$item['actionId']] = [
                    'permission' => $item['permission'],
                    'options' => $item['options'],
                ];

                return $carry;
            }, []);

            $widgetPermission['*'] = [];  // Первый уровень - widgetId
            $widgetPermission['*']['*'] =  // Второй уровень - actionId
                [
                    'permission' => (Setting::Get('authz.defaultPolicy'))[AuthzConstant::RESOURCE_TYPE_WIDGET],
                    'options' => [],
                ];

            $pagePermissionOptions = count($pagePermissionList) >= 1 ? $pagePermissionList[0]['options'] : [];

            $templateData = [
                'title' => $title,
                'permissionOptions' => $pagePermissionOptions,
                '_js' => [
                    'permission' => $widgetPermission,
                ],
            ];

            echo Util::RenderTemplate("app/template/{$roleName}-{$resource}.php");
        } catch (\Throwable $e) {

            $resource = AuthzConstant::RESOURCE_PAGE_MESSAGE;

            if ($e instanceof MWException) {
                /** @var MWException $mwe */
                $mwe = $e;
                $errCode = $mwe->errCode();

                switch ($errCode) {
                    case MWI18nHelper::ERR_UNKNOWN:
                        $log->critical($e->logMessage());
                        $templateData = [
                            'message' => [
                                'code' => MWI18nHelper::ERR_UNKNOWN,
                                'args' => [],
                            ],
                        ];
                        break;
                    case MWI18nHelper::ERR_AUTHORIZATION_NEEDED:
                        $log->warning($e->logMessage());
                        $templateData = [
                            'message' => [
                                'code' => MWI18nHelper::ERR_AUTHORIZATION_NEEDED,
                                'args' => [],
                            ],
                        ];
                        break;
                    default:
                        $log->error($e->logMessage());
                        $templateData = [
                            'message' => [
                                'code' => MWI18nHelper::ERR_UNKNOWN,
                                'args' => [],
                            ],
                        ];
                        break;
                }
            } else if ($e instanceof \PDOException) {
                $log->error($e->getMessage());
                $templateData = [
                    'message' => [
                        'code' => MWI18nHelper::ERR_DB_CONNECTION_FAILED,
                        'args' => [],
                    ],
                ];
            } else {
                $log->error($e->getMessage());
                $templateData = [
                    'message' => [
                        'code' => MWI18nHelper::ERR_UNKNOWN,
                        'args' => [],
                    ],
                ];
            }

            $templateData['title'] = $title;

            echo Util::RenderTemplate("app/template/{$resource}.php");
        } finally {
            global $startTime;

            $log->info('timing', Util::CalcExecutionTime($startTime));
            $log->notice('finish');
        }
    }
}
