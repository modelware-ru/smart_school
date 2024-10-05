<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$subjectId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $subjectId !== 0) ? $query['action'] : '';

if ($subjectId === 0) {
    $subject = [
        'id' => 0,
        'name' => '',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'subjectId' => $subjectId,
    ];

    list($res, $data) = (new DomainModule())->getSubjectById($args);

    $subject = $res->getData();
}
$templateData['_js']['subject'] = $subject;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_subject_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="subject-list.php">Список предметов</a></li>
                    <?php if ($subjectId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новый предмет</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Предмет "<?= $subject['name']?>"</span></li>
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