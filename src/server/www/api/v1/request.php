<?php
require_once '../../defines.php';
require_once '../../' . PATH_TO_INCLUDE;
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

    $log->info('parameters:', ['resource' => $resource, 'payload' => $payload, 'query' => $query]);

    if (empty($resource)) {
        MWException::ThrowEx(
            errCode: MWI18nHelper::ERR_WRONG_REQUEST_PARAMETERS,
            logData: [$resource, \json_encode($payload, JSON_PRETTY_PRINT)],
        );
    }

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

            require_once 'api/v1/service/account.php';
            $res = srv_sign_in($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_PARALLEL:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['number'] = $payload['number'];
            $args['showInGroup'] = $payload['showInGroup'];
            $args['order'] = $payload['order'];

            require_once 'api/v1/service/parallel.php';
            $res = srv_save_parallel($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_PARALLEL:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/parallel.php';
            $res = srv_remove_parallel($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_GROUP:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['parallelId'] = $payload['parallelId'];
            $args['order'] = $payload['order'];

            require_once 'api/v1/service/group.php';
            $res = srv_save_group($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_GROUP:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/group.php';
            $res = srv_remove_group($args);

            break;
        case AuthzConstant::RESOURCE_API_BLOCK_TEACHER:
            $args['id'] = $payload['id'];
            $args['action'] = $payload['action'];

            require_once 'api/v1/service/teacher.php';
            $res = srv_block_teacher($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_TEACHER:
            $args['id'] = $payload['id'];
            $args['login'] = $payload['login'];
            $args['email'] = $payload['email'];
            $args['password'] = $payload['password'];
            $args['firstName'] = $payload['firstName'];
            $args['lastName'] = $payload['lastName'];
            $args['middleName'] = $payload['middleName'];
            $args['roleStateId'] = $payload['roleStateId'];

            require_once 'api/v1/service/teacher.php';
            $res = srv_save_teacher($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_TEACHER:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/teacher.php';
            $res = srv_remove_teacher($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_SUBJECT:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];

            require_once 'api/v1/service/subject.php';
            $res = srv_save_subject($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_SUBJECT:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/subject.php';
            $res = srv_remove_subject($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_STUDENT:
            $args['id'] = $payload['id'];
            $args['firstName'] = $payload['firstName'];
            $args['lastName'] = $payload['lastName'];
            $args['middleName'] = $payload['middleName'];

            require_once 'api/v1/service/student.php';
            $res = srv_save_student($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_STUDENT:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/student.php';
            $res = srv_remove_student($args);

            break;
        case AuthzConstant::RESOURCE_API_CHANGE_CLASS:
            $args['startDate'] = $payload['startDate'];
            $args['classLetter'] = $payload['classLetter'];
            $args['parallelId'] = $payload['parallelId'];
            $args['reason'] = $payload['reason'];
            $args['studentIdList'] = $payload['studentIdList'];

            require_once 'api/v1/service/student.php';
            $res = srv_change_class($args);

            break;
        case AuthzConstant::RESOURCE_API_CHANGE_GROUP:
            $args['startDate'] = $payload['startDate'];
            $args['groupId'] = $payload['groupId'];
            $args['reason'] = $payload['reason'];
            $args['studentIdList'] = $payload['studentIdList'];

            require_once 'api/v1/service/student.php';
            $res = srv_change_group($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_TOPIC:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];

            require_once 'api/v1/service/topic.php';
            $res = srv_save_topic($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_TOPIC:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/topic.php';
            $res = srv_remove_topic($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_CATEGORY_TAG:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['removedTagIdList'] = $payload['removedTagIdList'];
            $args['newTagList'] = $payload['newTagList'];

            require_once 'api/v1/service/categoryTag.php';
            $res = srv_save_categoryTag($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_CATEGORY_TAG:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/categoryTag.php';
            $res = srv_remove_categoryTag($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_SCHOOL_YEAR:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['startDate'] = $payload['startDate'];
            $args['finishDate'] = $payload['finishDate'];
            $args['isCurrent'] = $payload['isCurrent'];

            require_once 'api/v1/service/schoolYear.php';
            $res = srv_save_schoolYear($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_SCHOOL_YEAR:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/schoolYear.php';
            $res = srv_remove_schoolYear($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_SERIE:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['removedTaskIdList'] = $payload['removedTaskIdList'];
            $args['newTaskList'] = $payload['newTaskList'];

            require_once 'api/v1/service/serie.php';
            $res = srv_save_serie($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_SERIE:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/serie.php';
            $res = srv_remove_serie($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_LESSON:
            $args['id'] = $payload['id'];
            $args['date'] = $payload['date'];
            $args['groupId'] = $payload['groupId'];
            $args['subjectId'] = $payload['subjectId'];
            $args['serieList'] = $payload['serieList'];

            require_once 'api/v1/service/lesson.php';
            $res = srv_save_lesson($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_LESSON:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/lesson.php';
            $res = srv_remove_lesson($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_TEACHER_GROUP:
            $args['groupId'] = $payload['groupId'];
            $args['schoolYearId'] = $payload['schoolYearId'];
            $args['teacherList'] = $payload['teacherList'];

            require_once 'api/v1/service/teacher.php';
            $res = srv_save_teacherGroup($args);

            break;
        case AuthzConstant::RESOURCE_API_ADD_SERIE_TO_LESSON:
            $args['lessonId'] = $payload['lessonId'];
            $args['serieId'] = $payload['serieId'];
            $args['groupId'] = $payload['groupId'];
            $args['studentClassList'] = $payload['studentClassList'];
            $args['studentHomeList'] = $payload['studentHomeList'];

            require_once 'api/v1/service/lesson.php';
            $res = srv_add_serie_to_lesson($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_SERIE_FROM_LESSON:
            $args['groupId'] = $payload['groupId'];
            $args['lessonId'] = $payload['lessonId'];
            $args['serieId'] = $payload['serieId'];
            $args['studentClassList'] = $payload['studentClassList'];
            $args['studentHomeList'] = $payload['studentHomeList'];

            require_once 'api/v1/service/lesson.php';
            $res = srv_remove_serie_from_lesson($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_STUDENT_SOLUTION:
            $args['taskList'] = $payload['taskList'];
            $args['studentSerieId'] = $payload['studentSerieId'];

            require_once 'api/v1/service/student.php';
            $res = srv_save_student_solution($args);

            break;
        case AuthzConstant::RESOURCE_API_SAVE_TASK:
            $args['id'] = $payload['id'];
            $args['name'] = $payload['name'];
            $args['topicId'] = $payload['topicId'];

            require_once 'api/v1/service/task.php';
            $res = srv_save_task($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_TASK:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/task.php';
            $res = srv_remove_task($args);

            break;
        case AuthzConstant::RESOURCE_API_ADD_HOME_SERIE_TO_STUDENT:
            $args['serieId'] = $payload['serieId'];
            $args['studentId'] = $payload['studentId'];
            $args['groupId'] = $payload['groupId'];
            $args['date'] = $payload['date'];

            require_once 'api/v1/service/serie.php';
            $res = srv_add_home_serie_to_student($args);

            break;
        case AuthzConstant::RESOURCE_API_REMOVE_HOME_SERIE_FROM_STUDENT:
            $args['id'] = $payload['id'];

            require_once 'api/v1/service/serie.php';
            $res = srv_remove_home_serie_from_student($args);

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
