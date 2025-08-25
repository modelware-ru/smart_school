<?php

use MW\Shared\Util;
use MW\Module\Domain\Student\Main as StudentModule;
use MW\Module\Domain\Group\Main as GroupModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$studentIdList = isset($query['ids']) ? array_map('intval', explode(',', $query['ids'])) : [];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentIdList' => $studentIdList,
];

list($res, $data) = (new StudentModule())->getStudentByIdList($args);

$classParallelList = array_reduce($res->getData(), function ($carry, $item) {
    if (!is_null($item['classParallelId']) && !in_array($item['classParallelId'], $carry)) {
        $carry[] = $item['classParallelId'];
    }

    return $carry;
}, []);

if (count($classParallelList) !== 1) {
?>
    <!DOCTYPE html>
    <html lang='<?= $langId ?>' data-bs-theme='auto'>

    <head>
        <?= Util::RenderTemplate('app/template/shared/head.php') ?>
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
                        <li class="breadcrumb-item"><a href="student-list.php">Список учеников</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Смена группы</span></li>
                    </ol>
                </nav>
            </div>
            <div class="alert alert-info rounded-0 my-3" role="alert">
                <div>
                    <p class="m-0">Невозможно изменить группу, так как ученики учатся на разных параллелях.</p>
                </div>
            </div>
        </div>
        <script src='js/bootstrap.bundle.min.js'></script>
    </body>

    </html>
<?php
    return;
}

$studentList = array_map(function ($item) {
    return [
        'id' => $item['id'],
        'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
        'class' => "{$item['classNumber']} {$item['classLetter']}",
    ];
}, $res->getData());

$studentIdList = array_map(function ($item) {
    return [
        'id' => $item['id'],
    ];
}, $studentList);

$parallelId = $classParallelList[0];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'parallelId' => $parallelId,
];

list($res, $data) = (new GroupModule())->getGroupListByParallelId($args);

$groupList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите группу",
        "disabled" => true,
    ]
]);

$templateData['_js']['groupList'] = $groupList;
$templateData['_js']['studentIdList'] = $studentIdList;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_studentListChangeGroup_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="student-list.php">Список учеников</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Смена группы</span></li>
                </ol>
            </nav>
        </div>
        <?php
        if ($res->isOk() && count($studentList) > 0) {
        ?>
            <table class="table table-bordered my-3">
                <thead>
                    <tr class="table-active border-dark-subtle">
                        <th scope="col" class="text-end fit">#</th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Класс</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($studentList as $key => $item) {
                    ?>
                        <tr class="align-middle">
                            <th scope="row" class="text-end text-nowrap"><?= $key + 1 ?></th>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['class'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="alert alert-info rounded-0 my-3" role="alert">
                <div>
                    <p class="m-0">Не выбран ни один ученик.</p>
                </div>
            </div>
        <?php
        }
        ?>
        <div id="main" class="d-flex flex-column">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>