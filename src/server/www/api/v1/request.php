<?php
require_once '../../../app/include.php';
require_once 'app/init.php';

use MW\Service\Authz\Constant as AuthzConstant;
use MW\Service\Authz\Main as AuthzService;
use MW\Shared\Constant as SharedConstant;
use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\App\Setting;

global $accountId;
global $roleId;
global $roleStateId;

try {
    $log = Logger::Init('api-request');
    $log->notice('start');

    list($resource, $payload, $query) = Util::HandlePOST();

    $resourceType = AuthzConstant::RESOURCE_TYPE_API;
    $actionId = AuthzConstant::ACTION_API_CALL;
    $apiPermissionList = AuthzService::GetPermissionList([
        'accountId' => $accountId,
        'roleId' => $roleId,
        'roleStateId' => $roleStateId,
        'resourceType' => $resourceType,
        'resource' => $resource,
        'actionId' => $actionId,
    ]);

    $apiPermission = count($apiPermissionList) >= 1 ? $apiPermissionList[0]['permission'] : (Setting::Get('authz.defaultPolicy'))[$resourceType];

    if ($apiPermission !== AuthzConstant::PERMISSION_ALLOW) {
        MWException::ThrowEx(
            errCode: MWI18nHelper::ERR_AUTHORIZATION_NEEDED,
            logData: [$roleId, $resource, $actionId],
        );
    }

    $log->notice('parameters', array('resource' => $resource, 'payload' => Util::MaskData($payload)));

    require_once 'api/v1/checker.php';
    if (!check_type_parameters($resource, $payload)) {
        MWException::ThrowEx(
            errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
            logData: [$resource, json_encode(Util::MaskData($payload), JSON_PRETTY_PRINT)],
        );
    }

    $apiPermissionOptions = count($apiPermissionList) >= 1 ? $apiPermissionList[0]['options'] : [];

    Logger::Init("api-{$resource}", true);

    $args = [
        'permissionOptions' => $apiPermissionOptions,
    ];
    switch ($resource) {
        case AuthzConstant::RESOURCE_API_SIGN_IN:
            $args['login'] = $payload['login'];
            $args['password'] = $payload['password'];

            require_once 'api/v1/service/app.php';
            $res = app_sign_in($args);

            break;
    }

    DBManager::Commit();
} catch (MWException $e) {
    DBManager::Rollback();

    $httpStatus = $e->httpStatus();
    http_response_code($e->httpStatus());
    $res = Util::MakeErrorOperationResult($e->errCode(), $e->errData());

    switch ($httpStatus) {
        case SharedConstant::HTTP_INTERNAL_SERVER_ERROR:
            $log->critical($e->logMessage());
            break;
        case SharedConstant::HTTP_UNAUTHORIZED:
            $log->warning($e->logMessage());
            break;
        default:
            $log->error($e->logMessage());
            break;
    }
} catch (\Throwable $e) {
    DBManager::Rollback();

    $log->error($e->getMessage());
    http_response_code(SharedConstant::HTTP_INTERNAL_SERVER_ERROR);
    $res = Util::MakeErrorOperationResult(MWI18nHelper::ERR_UNKNOWN);
} finally {
    if (empty($res)) {
        $res = Util::MakeSuccessOperationResult();
    }

    echo $res->json();

    global $startTime;

    $log->info('timing', Util::CalcExecutionTime($startTime));
    $log->notice('finish');
}
