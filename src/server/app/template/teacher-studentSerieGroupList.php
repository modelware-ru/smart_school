<?php

use MW\Shared\Util;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;
use MW\Module\Domain\Student\Main as StudentModule;
use MW\Service\Authz\Constant as AuthzConstant;
use MW\Module\Domain\Group\Main as GroupModule;
use MW\Module\Domain\Serie\Main as SerieModule;

global $templateData;
global $langId;

$roleName = AuthzConstant::GetRoleName($roleId);

$query = Util::HandleGET();

$studentId = isset($query['id']) ? intval($query['id']) : 0;
$groupId = isset($query['groupId']) ? intval($query['groupId']) : 0;
$schoolYearId = isset($query['schoolYearId']) ? intval($query['schoolYearId']) : 0;
$startDate = null;
$finishDate = null;

// getSchoolYearList
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new SchoolYearModule())->getSchoolYearList($args);

$schoolYearList = [];
foreach ($res->getData() as $item) {
    $isSelected = false;
    if (($schoolYearId === 0 && $item['isCurrent']) || ($schoolYearId === $item['id'])) {
        $isSelected = true;
        $startDate = $item['startDate'];
        $finishDate = $item['finishDate'];
    }

    $schoolYearList[] = [
        'id' => strval($item['id']),
        'title' => $item['name'] . ' &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;' . $item['startDate'] . '&nbsp;&nbsp;-&nbsp;&nbsp;' . $item['finishDate'],
        'isCurrent' => $item['isCurrent'],
        'isSelected' => $isSelected,
    ];
}


// getStudentById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentId' => $studentId,
];
list($res, $data) = (new StudentModule())->getStudentById($args);

$student = $res->getData();

// getStudentSerieGroupList
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentId' => $studentId,
    'groupId' => $groupId,
];
list($res, $data) = (new StudentModule())->getStudentSerieGroupList($args);

$studentSerieList = $res->getData();

// getGroupById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
];

list($res, $data) = (new GroupModule())->getGroupById($args);

$groupName = ($res->getData())['name'];
$parallelName = ($res->getData())['parallelName'];
$parallelNumber = ($res->getData())['parallelNumber'];

// getStudentById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];
list($res, $data) = (new SerieModule())->getSerieList($args);

$serieList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите серию",
        "disabled" => false,
    ]
]);

$templateData['_js']['studentId'] = $studentId;
$templateData['_js']['groupId'] = $groupId;
$templateData['_js']['serieList'] = $serieList;
$templateData['_js']['schoolYear'] = [
    'startDate' => $startDate,
    'finishDate' => $finishDate,
];
?>

<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_studentSerieGroupList_bundle.js' defer></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/teacher-closer.php') ?>
        </nav>
        <hr class='m-0' />
        <table class="table table-striped mt-5">
            <tbody>
                <tr>
                    <th scope="row">Ученик</th>
                    <td><?= $student['lastName'] ?> <?= $student['firstName'] ?> <?= $student['middleName'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Параллель</th>
                    <td><?= $parallelName ?> [<?= $parallelNumber ?>]</td>
                </tr>
                <tr>
                    <th scope="row">Группа</th>
                    <td><?= $groupName ?></td>
                </tr>
            </tbody>
        </table>

        <div id="main">
            <div id="student-serie-group-list-header"></div>
        </div>

        <?php
        if (count($studentSerieList) > 0) {
        ?>

            <table class="table table-primary table-striped table-hover table-bordered clickable-rows my-3">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Серия</th>
                        <th scope="col">Тип серии</th>
                        <th scope="col">Дата выдачи</th>
                        <th scope="col">Занятие</th>
                        <th scope="col">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($studentSerieList as $key => $item) {
                    ?>
                        <tr data-id="<?= $item['id'] ?>">
                            <th scope="row" class="text-end text-nowrap"><?= $key + 1 ?></th>
                            <td><?= $item['serieName'] ?></td>
                            <td><?= $item['serieType'] === 'HOME' ? "Домашняя" : "Классная" ?></td>
                            <td><?= $item['serieDate'] ?></td>
                            <?php if (is_null($item['subjectName'])) { ?>
                                <td></td>
                            <?php } else { ?>
                                <td><?= $item['subjectName'] ?> / <?= $item['lessonDate'] ?></td>
                            <?php } ?>
                            <td class="p-1">
                                <button data-action="remove" data-id="<?= $item['id'] ?>" class='btn btn-outline-danger btn-sm disabled'><i class="bi bi-trash"></i></button>
                            </td>
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
                    <p class="m-0">Не найдено ни одно серии для данного ученика.</p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            for (const item of document.querySelectorAll('button[data-action="remove"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`student-serie.php?id=${item.dataset.id}&studentId=<?= $studentId ?>&groupId=<?= $groupId ?>&schoolYearId=<?= $schoolYearId ?>&action=remove`);
                });
            }

            for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.open(`student-serie-solution.php?id=${item.dataset.id}`, '_blank');
                });
            }
        });
    </script>
</body>

</html>