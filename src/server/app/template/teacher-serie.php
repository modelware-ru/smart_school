<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$serieId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $serieId !== 0) ? $query['action'] : '';

if ($serieId === 0) {
    $serie = [
        'id' => 0,
        'name' => '',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'serieId' => $serieId,
    ];

    list($res, $data) = (new DomainModule())->getSerieById($args);

    $serie = $res->getData();
}
$templateData['_js']['serie'] = $serie;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_serie_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="serie-list.php">Список серий</a></li>
                    <?php if ($serieId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая серия</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Серия "<?= $serie['name']?>"</span></li>
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