<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');

require_once 'vendor/autoload.php';

use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\Util;

$authzPath = '../../server/service/authz';
$authzJsPath = '../../client/script/shared';
try {
    $log = Logger::Init('tool-authz', false, 'path_localhost');
    $log->notice('start');

    $db = DBManager::GetConnection('localhost');
    //
    $roleList = $db->select('SELECT id, code_name, name FROM authz__role');
    $actionList = $db->select('SELECT id, code_name, name FROM authz__action');
    $resourceTypeList = $db->select('SELECT DISTINCT resource_type, UPPER(SUBSTRING(resource_type, 7)) code_name FROM authz__action');
    $roleStateList = $db->select(
        'SELECT ar.id, ar.code_name role_code_name, ar.name role_name, ars.code_name state_code_name, ars.name state_name, ars.id state_id FROM authz__role_state ars' .
        ' JOIN authz__role ar ON ar.id = ars.role_id' .
        ' ORDER BY ar.id'
    );

    $resourceList = [];
    foreach ($resourceTypeList as $value) {
        $resourceList[$value['code_name']] = $db->select("SELECT id, code_name FROM {$value['resource_type']}");
    }

    ob_start();
    require 'authz-Constant.php.php';
    $res = ob_get_contents();
    ob_end_clean();
    file_put_contents("{$authzPath}/Constant.php", $res);

    ob_start();
    require 'authz-action.js.php';
    $res = ob_get_contents();
    ob_end_clean();
    file_put_contents("{$authzJsPath}/action.js", $res);

} catch (MWException $e) {
    $log->error($e->logMessage());
    echo 'MWException ' . date('Y-m-d H:i') . PHP_EOL;
} catch (\Throwable $e) {
    $log->error($e->getMessage());
    echo 'Error ' . date('Y-m-d H:i') . PHP_EOL;
} finally {
    $log->info('timing', Util::CalcExecutionTime(microtime(true)));
    $log->notice('finish');
    echo 'Done ' . date('Y-m-d H:i') . PHP_EOL;
}
