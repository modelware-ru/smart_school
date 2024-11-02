<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');

$requestUID = uniqid(time(), true);
$startTime = microtime(true);

require_once 'vendor/autoload.php';

use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\Util;

$options = getopt('t::c:r:');

if (!key_exists('c', $options)) {
    echo "Usage: -c <connectionKey> -t -r <realm>" . PHP_EOL;
    exit();
}

$connectionName = $options['c'];
$realmName = $options['r'];

$isTest = FALSE;
if ($options && key_exists('t', $options)) {
    $isTest = TRUE;
    echo "Test is not supported" . PHP_EOL;
    exit();
}

$scriptList = [
    0 => "../../database/main-clean-{$connectionName}.sql",
    1 => '../../database/authz/authz-schema.sql',
    2 => "../../database/authz/authz-data-{$realmName}.sql",
    3 => '../../database/main-schema.sql',
    4 => "../../database/main-data-{$realmName}.sql",
];

try {
    $log = Logger::Init('tool-sql-script', false, 'path_localhost');
    $log->notice('start');

    $db = DBManager::GetConnection($connectionName);

    // $res = $db->select("SELECT * FROM main__group;");
    // var_dump($res);
    // echo "ok";
    // exit();
    // if ($isTest) {
    //     $scriptList = $scriptTestList;
    //     $db = DBManager::GetConnection('main-test');
    // }

    echo 'Обновление ' . ($isTest ? 'тестовой' : 'основной') . ' базы на ' . $connectionName . PHP_EOL;

    foreach ($scriptList as $script) {
        $query = file_get_contents($script);
        if ($query === false || empty($query)) {
            $log->notice("skip process '{$script}'" . PHP_EOL);
            continue;
        }
        $log->notice("before processing '{$script}'");
        echo $script;
        $db->exec($query);
        $log->notice("after processing '{$script}'" . PHP_EOL);
        echo ' - done' . PHP_EOL;
    }
} catch (MWException $e) {
    $log->error($e->logMessage());
    echo PHP_EOL . 'MWException: ' . $e->logMessage() . PHP_EOL;
} catch (\Throwable $e) {
    $log->error($e->getMessage());
    echo PHP_EOL . 'Error: ' . $e->getMessage() . PHP_EOL;
} finally {
    $log->info('timing', $res = Util::CalcExecutionTime($startTime));
    $log->notice('finish');
    echo 'Done: ' . date('Y-m-d H:i') . PHP_EOL;
    echo 'Execution Time: ' . $res['execution'] . PHP_EOL;
}
