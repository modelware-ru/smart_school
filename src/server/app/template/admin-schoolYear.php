<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$schoolYearId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $schoolYearId !== 0) ? $query['action'] : '';

if ($schoolYearId === 0) {
    $schoolYear = [
        'id' => 0,
        'name' => '',
        'startDate' => '',
        'finishDate' => '',
        'isCurrent' => FALSE,
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'schoolYearId' => $schoolYearId,
    ];

    list($res, $data) = (new DomainModule())->getSchoolYearById($args);

    $schoolYear = $res->getData();
}

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getCurrentSchoolYearAndCount($args);

$schoolYear['currentId'] = ($res->getData())[0]['currentId'];
$schoolYear['count'] = ($res->getData())[0]['count'];

$templateData['_js']['schoolYear'] = $schoolYear;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_schoolYear_bundle.js' defer></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/admin-navigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="schoolyear-list.php">Список учебных годов</a></li>
                    <?php if ($schoolYearId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новый учебный год</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Учебный год "<?= $schoolYear['name']?>"</span></li>
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