<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');

$requestUID = uniqid(time(), true);
$startTime = microtime(true);

require_once 'vendor/autoload.php';

use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\Util;

$options = getopt('t::');

$isTest = FALSE;
if ($options && !is_null($options['t'])) {
    $isTest = TRUE;
}

$scriptShared = [
    1 => '../../database/authz/authz-schema.sql',
    2 => '../../database/authz/authz-data.sql',
    3 => '../../database/main-schema.sql',
    4 => '../../database/main-data.sql',
    5 => '../../database/main-data-authz.sql',
];

$i = count($scriptShared);
$scriptOnlyForTest = [
    // $i + 1 => '../../database/authz/authz-data-test.sql',
    // $i + 2 => '../../database/croner/croner-data-test.sql',
    // $i + 3 => '../../database/main-data-test.sql',
    // $i + 4 => '../../database/main-data-authz-test.sql',
    // $i + 5 => '../../database/main-data-croner-test.sql',
];

$scriptList = ['../../database/main-clean.sql'] + $scriptShared;
// $scriptList = ['../../database/main-clean-majordomo.sql'] + $scriptShared;

$scriptTestList = ['../../database/main-clean-test.sql'] + $scriptShared + $scriptOnlyForTest;

try {
    $log = Logger::Init('tool-sql-script', false, 'path_localhost');
    $log->notice('start');

    $db = DBManager::GetConnection('localhost-mariaDB');

    if ($isTest) {
        $scriptList = $scriptTestList;
        $db = DBManager::GetConnection('main-test');
    }

    echo 'Обновление ' . ($isTest ? 'тестовой' : 'основной') . ' базы' . PHP_EOL;

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
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
} finally {
    $log->info('timing', $res = Util::CalcExecutionTime($startTime));
    $log->notice('finish');
    echo 'Done: ' . date('Y-m-d H:i') . PHP_EOL;
    echo 'Execution Time: ' . $res['execution'] . PHP_EOL;
}
