<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$resource = $templateData['resource'];

$query = Util::HandleGET();

$topicId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $topicId !== 0) ? $query['action'] : '';

if ($topicId === 0) {
    $topic = [
        'id' => 0,
        'name' => '',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'topicId' => $topicId,
    ];

    list($res, $data) = (new DomainModule())->getTopicById($args);

    $topic = $res->getData();
}
$templateData['_js']['topic'] = $topic;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/<?=$resource?>_bundle.js' defer></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/teacher-navigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="topic-list.php">Список тем задач</a></li>
                    <?php if ($topicId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая тема</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Тема "<?= $topic['name']?>"</span></li>
                    <?php } ?>
                </ol>
            </nav>
        </div>
        <div id="main" class="d-flex flex-column">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>