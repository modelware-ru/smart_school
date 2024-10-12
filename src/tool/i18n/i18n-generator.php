<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');

$requestUID = uniqid(time(), true);
$startTime = microtime(true);

require_once 'vendor/autoload.php';

use MW\Shared\DBManager;
use MW\Shared\Logger;
use MW\Shared\MWException;
use MW\Shared\Util;

$jsPath = '../../client/script/shared/i18n';
$phpPath = '../../server/shared';
require_once './i18n-data.php';

try {
    $log = Logger::Init('tool-i18n', false, 'path_localhost');
    $log->notice('start');

    $langs = ['ru', 'en'];

    // js
    foreach ($langs as $langId) {
        ob_start();
        require 'i18n-lang.js.php';
        $res = ob_get_contents();
        ob_end_clean();
        file_put_contents("{$jsPath}/i18n.{$langId}.js", $res);
    }

    // php
    // Данные для названий страниц берутся из базы данных
    $db = DBManager::GetConnection('localhost');

    $pageList = $db->select('SELECT id, code_name, name FROM main__page');

    $i18n_PAGE_TITLE = [];
    foreach ($pageList as $page) {
        $nameList = json_decode($page['name'], true);
        preg_match_all('/[A-Z][^A-Z]*/', ucfirst($page['code_name']), $matches);
        $key = strtoupper(implode('_', $matches[0]));
        $i18n_PAGE_TITLE[$key] = $nameList;
    }

    ob_start();
    require 'i18n-MWI18nHelper.php.php';
    $res = ob_get_contents();
    ob_end_clean();
    file_put_contents("{$phpPath}/MWI18nHelper.php", $res);
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
