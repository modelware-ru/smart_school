<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');

$requestUID = uniqid(time(), true);
$startTime = microtime(true);

require_once 'vendor/autoload.php';

use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\Util;


$options = getopt('c:');

if (!key_exists('c', $options)) {
    echo "Usage: -c <connectionKey>" . PHP_EOL;
    exit();
}

$connectionName = $options['c'];

$authzPath = '../../server/service/authz';
$authzJsPath = '../../client/script/shared';
try {
    $log = Logger::Init('tool-authz', false, 'path_localhost');
    $log->notice('start');

    $db = DBManager::GetConnection($connectionName);
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

    foreach ($resourceTypeList as $key => $value) {
        foreach ($resourceList[$value['code_name']] as $key1 => $value1) {
            $codeName = $resourceList[$value['code_name']][$key1]['code_name'];
            preg_match_all('/[A-Z][^A-Z]*/', ucfirst($codeName), $matches);
            $newCodeName = strtoupper(implode('_', $matches[0]));
            $resourceList[$value['code_name']][$key1]['title'] = $newCodeName;
        }
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
    echo PHP_EOL . 'MWException: ' . $e->logMessage() . PHP_EOL;
} catch (\Throwable $e) {
    $log->error($e->getMessage());
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
} finally {
    $log->info('timing', $res = Util::CalcExecutionTime($startTime));
    $log->notice('finish');
    echo 'Done: ' . date('Y-m-d H:i') . PHP_EOL;
    echo 'Execution Time: ' . $res['execution'] . PHP_EOL;
}
