<?php

use MW\Shared\Util;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;
use MW\Module\Domain\Group\Main as GroupModule;
use MW\Module\Domain\Student\Main as StudentModule;
use MW\Service\Authz\Constant as AuthzConstant;

global $templateData;
global $langId;
global $roleId;
global $userId;

$roleName = AuthzConstant::GetRoleName($roleId);

$query = Util::HandleGET();

$groupId = isset($query['id']) ? intval($query['id']) : 0;
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


$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
];

list($res, $data) = (new GroupModule())->getGroupById($args);

$groupName = ($res->getData())['name'];
$parallelName = ($res->getData())['parallelName'];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
    'startDate' => $startDate,
    'finishDate' => $finishDate,
];
list($res, $data) = (new StudentModule())->getStudentListForGroup($args);

?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate("app/template/shared/{$roleName}-navigator.php") ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="group-list.php">Список групп по параллелям</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Ученики группы "<?= $groupName ?>" [<?= $parallelName ?>]</span></li>
                </ol>
            </nav>
        </div>

        <?php
        if (count($schoolYearList) > 0) {
        ?>
            <select id="selectSchoolYear" class="form-select mt-5 mb-3" aria-label="Учебные года" disabled>
                <?php
                foreach ($schoolYearList as $item) {
                    $selected = '';
                    if ($item['isSelected']) {
                        $selected = 'selected';
                    }
                ?>
                    <option value="<?= $item['id'] ?>" <?= $selected ?>><?= $item['title'] ?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        $studentList = $res->getData();
        if ($res->isOk() && count($studentList) > 0) {
        ?>
            <table class="table table-hover table-bordered clickable-rows my-3">
                <thead>
                    <tr class="table-active border-dark-subtle">
                        <th scope="col" class="text-end fit">#</th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Интервал принадлежности группе</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                    foreach ($studentList as $key => $item) {
                        $index++;
                    ?>
                        <tr class="align-middle" data-id="<?= $item['id'] ?>">
                            <th scope="row" class="text-end text-nowrap"><?= $index ?></th>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['startDate'] ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?= $item['finishDate'] ?></td>
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
                    <p class="m-0">Не найдено ни одно ученика.</p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr:not([noclick])')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.open(`student-serie-group-list.php?id=${item.dataset.id}`, '_blank');
                });
            }
        });
    </script>
</body>

</html>