<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$resource = $templateData['resource'];

$query = Util::HandleGET();

$categoryTagId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $categoryTagId !== 0) ? $query['action'] : '';

if ($categoryTagId === 0) {
    $categoryTag = [
        'id' => 0,
        'name' => '',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'categoryTagId' => $categoryTagId,
    ];

    list($res, $data) = (new DomainModule())->getCategoryTagById($args);

    $categoryTag = $res->getData();
}
$templateData['_js']['categoryTag'] = $categoryTag;
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
            <?= Util::RenderTemplate('app/template/shared/adminNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="category-tag-list.php">Список категорий</a></li>
                    <?php if ($categoryTagId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая категория</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Тема "<?= $categoryTag['name']?>"</span></li>
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