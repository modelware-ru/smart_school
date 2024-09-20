<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;
use MW\Service\Authz\Constant as AuthzConstant;

global $templateData;
global $langId;
global $roleId;
global $userId;

$roleName = AuthzConstant::GetRoleName($roleId);

$resource = $templateData['resource'];

$query = Util::HandleGET();

$groupId = isset($query['id']) ? intval($query['id']) : 0;
$schoolYearId = isset($query['schoolYearId']) ? intval($query['schoolYearId']) : 0;
$selecterSchoolYearId = 0;
$schoolYearCurrentId = 0;
$startDate = null;
$finishDate = null;

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getSchoolYearList($args);

$schoolYearList = [];
foreach ($res->getData() as $item) {
    $isSelected = false;
    if (($schoolYearId === 0 && $item['isCurrent']) || ($schoolYearId === $item['id'])) {
        $isSelected = true;
        $selecterSchoolYearId = $item['id'];
        $startDate = $item['startDate'];
        $finishDate = $item['finishDate'];
    }

    if ($schoolYearCurrentId === 0 || $item['isCurrent']) {
        $schoolYearCurrentId = $item['id'];
    }

    $schoolYearList[] = [
        'id' => strval($item['id']),
        'title' => $item['name'] . ' &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;' . $item['startDate'] . '&nbsp;&nbsp;-&nbsp;&nbsp;' . $item['finishDate'],
        'isCurrent' => $item['isCurrent'],
        'isSelected' => $isSelected,
    ];
}

if (count($schoolYearList) === 0) {
    $schoolYearId = -1;
}

$schoolYearList[] = [
    'id' => '-1',
    'title' => 'Все занятия',
    'isCurrent' => false,
    'isSelected' => $schoolYearId === -1,
];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
];

list($res, $data) = (new DomainModule())->getGroupById($args);

$groupName = ($res->getData())['name'];
$parallelName = ($res->getData())['parallelName'];

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
    'startDate' => $startDate,
    'finishDate' => $finishDate,
];
list($res, $data) = (new DomainModule())->getLessonListForGroup($args);

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
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Расписание группы "<?= $groupName ?>" [<?= $parallelName ?>]</span></li>
                </ol>
            </nav>
        </div>

        <?php
        if (count($schoolYearList) > 0) {
            if ($selecterSchoolYearId === 0) {
                $selecterSchoolYearId = $schoolYearCurrentId;
            }
        ?>
            <select id="selectSchoolYear" class="form-select mt-5 mb-3" aria-label="Учебные года">
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
            <div class="d-flex justify-content-end">
                <a href="lesson.php?id=0&groupId=<?= $groupId ?>&schoolYearId=<?= $schoolYearId ?>" class="btn btn-success">
                    <i class="bi bi-plus-circle me-3"></i>
                    <span role="status">Добавить</span>
                </a>
            </div>

        <?php
        }
        $itemList = $res->getData();
        if ($res->isOk() && count($itemList) > 0) {
        ?>
            <table class="table table-hover table-bordered clickable-rows my-3">
                <thead>
                    <tr class="table-active border-dark-subtle">
                        <th scope="col" class="text-end fit">#</th>
                        <th scope="col" class="text-end fit">Дата занятия</th>
                        <th scope="col">Серии</th>
                        <th scope="col" class="fit">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $curSubjectId = 0;
                    $index = 0;
                    foreach ($itemList as $key => $item) {
                        $index++;
                        if ($curSubjectId !== $item['subjectId']) {
                            $curSubjectId = $item['subjectId'];
                            $index = 1;
                    ?>
                            <tr class="align-middle table-primary" noclick>
                                <td colspan="4"><?= $item['subjectName'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr class="align-middle" data-id="<?= $item['id'] ?>">
                            <th scope="row" class="text-end text-nowrap"><?= $index ?></th>
                            <td><?= $item['date'] ?></td>
                            <td></td>
                            <td class="p-1">
                                <div class="d-flex gap-3">
                                <button data-action="edit" data-id="<?= $item['id'] ?>" class='btn btn-outline-primary btn-sm'><i class="bi bi-pencil"></i></button>

                                <?php if ($item['canBeRemoved']) { ?>
                                    <button data-action="remove" data-id="<?= $item['id'] ?>" class='btn btn-outline-danger btn-sm'><i class="bi bi-trash"></i></button>
                                <? } ?>
                                </div>
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
                    <p class="m-0">Не найдено ни одно занятие.</p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('selectSchoolYear');
            console.log(select);
            select.addEventListener('change', (e) => {
                e.stopPropagation();
                window.location.assign(`schedule.php?id=<?= $groupId ?>&schoolYearId=${e.target.value}`);
            });

            for (const item of document.querySelectorAll('button[data-action="remove"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`lesson.php?id=${item.dataset.id}&action=remove&schoolYearId=<?= $schoolYearId ?>`);
                });
            }

            for (const item of document.querySelectorAll('button[data-action="edit"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`lesson.php?id=${item.dataset.id}&schoolYearId=<?= $schoolYearId ?>`);
                });
            }

            for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr:not([noclick])')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`lesson-journal.php?lessonId=${item.dataset.id}&schoolYearId=<?= $schoolYearId ?>`);
                });
            }
        });
    </script>

</body>

</html>