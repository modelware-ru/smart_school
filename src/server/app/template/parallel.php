<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$parallelId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $parallelId !== 0) ? $query['action'] : '';

if ($parallelId === 0) {
    $parallel = [
        'id' => 0,
        'name' => '',
        'number' => '',
        'showInGroup' => false,
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'parallelId' => $parallelId,
    ];

    list($res, $data) = (new DomainModule())->getParallelById($args);

    $parallel = $res->getData();
}
$templateData['_js']['parallel'] = $parallel;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
</head>

<body>
    <div class="container">
        <nav id="nav" class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/adminNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="parallel-list.php">Список параллелей</a></li>
                    <?php if ($parallelId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая параллель</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Параллель "<?= $parallel['name']?>"</span></li>
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