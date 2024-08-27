<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$resource = $templateData['resource'];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getStudentList($args);

$studentList = array_map(function ($item) {
    return [
        'id' => $item['id'],
        'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
        'class' => "{$item['classNumber']} {$item['classLetter']}",
        'classParallelId' => $item['classParallelId'],
        'group' => $item['groupName'],
        'groupParallelId' => $item['groupParallelId'],
        'groupParallelNumber' => $item['groupParallelNumber'],
        'canBeRemoved' => $item['canBeRemoved'],
        'canBeShowHistory' => $item['canBeShowHistory'],
    ];
}, $res->getData());

$templateData['_js']['studentList'] = $studentList;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/<?= $resource ?>_bundle.js' defer></script>
</head>

<body>
    <div id="main" class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/adminNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div id="main" class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Список учеников</li>
                </ol>
            </nav>
        </div>
        <div id='student-list-header' class="d-flex flex-row-reverse justify-content-sm-end gap-1 gap-md-3 flex-wrap-reverse">
            <div class="d-flex justify-content-end">
                <a href="student.php?id=0" class="btn btn-success">
                    <i class="bi bi-plus-circle me-3"></i>
                    <span role="status">Добавить</span>
                </a>
            </div>
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>