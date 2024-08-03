<?php
use MW\Shared\MWI18nHelper;

global $templateData;
global $langId;

$title = (MWI18nHelper::Instance())->page($templateData['title'], $langId)();
$resource = $templateData['resource'];
if (isset($templateData['_js'])) {
    $_js = $templateData['_js'];
}
$_js['langId'] = $langId;
$version = 'Web Application Template. Версия ' . VERSION . ' ' . (defined('PHPUNIT') ? 'Тестовая сборка' : 'Обычная сборка');
?>
<meta charset='utf-8' />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='description' content='' />
<title><?=$title?></title>

<link rel='stylesheet' href='style/bootstrap.min.css' />
<link rel="stylesheet" href="style/bootstrap-icons.min.css">
<link rel='stylesheet' href='style/style.css'>
<script>
    window.app = JSON.parse('<?=addslashes(json_encode($_js, JSON_UNESCAPED_UNICODE))?>');
</script>
<script type='text/javascript' src='js/<?=$resource?>_bundle.js' defer></script>
<script>
    console.log('<?=$version?>');
</script>
