<?php

use MW\Shared\Util;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;
use MW\Module\Domain\Group\Main as GroupModule;
use MW\Module\Domain\Teacher\Main as TeacherModule;
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
        $schoolYearId = $item['id'];
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
];

list($res, $data) = (new GroupModule())->getGroupList($args);
$groupList = $res->getData();

if ($schoolYearId === 0) {
    $groupList = [];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'schoolYearId' => $schoolYearId,
    ];

    list($res, $data) = (new TeacherModule())->getTeacherGroupBySchoolYearId($args);

    $teacherGroup = array_reduce($res->getData(), function ($carry, $item) {
        $key = $item['groupId'];
        if (!array_key_exists($key, $carry)) {
            $carry[$key] = [];
        }

        if (!is_null($item['userId'])) {
            $carry[$key][] = [
                'id' => strval($item['userId']),
                'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
            ];
        }

        return $carry;
    }, []);

    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
    ];

    list($res, $data) = (new TeacherModule())->getTeacherList($args);

    $teacherList = array_reduce($res->getData(), function ($carry, $item) {
        $carry[] = [
            'value' => strval($item['id']),
            'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
            'disabled' => $item['roleStateId'] !== AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID,
        ];
        return $carry;
    }, [
        0 => [
            "value" => "0",
            "name" => "Выберите преподавателя",
            "disabled" => true,
        ]
    ]);

    $templateData['_js']['schoolYearId'] = $schoolYearId;
    $templateData['_js']['teacherGroup'] = $teacherGroup;
    $templateData['_js']['teacherList'] = $teacherList;
}

?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_teacherGroup_bundle.js' defer></script>
</head>

<body>
    <div id="main" class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate("app/template/shared/{$roleName}-navigator.php") ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Преподаватели в группах</span></li>
                </ol>
            </nav>
        </div>

        <?php
        if (count($schoolYearList) > 0) {
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

            <?php
            if (count($groupList) > 0) {
            ?>
                <table class="table table-hover table-bordered clickable-rows my-3">
                    <!-- <thead>
                        <tr class="table-active border-dark-subtle">
                            <th scope="col" class="text-end fit">#</th>
                            <th scope="col">Преподаватели</th>
                        </tr>
                    </thead> -->
                    <tbody>
                        <?php
                        $curParallelId = 0;
                        $curGroupId = 0;
                        $indexGroup = 0;
                        foreach ($groupList as $key => $item) {
                            if ($curParallelId !== $item['parallelId']) {
                                $curParallelId = $item['parallelId'];
                                $curGroupId = 0;
                                $indexGroup = 1;

                                if ($key !== 0) {
                        ?>
                                    <tr class="align-middle" noclick>
                                        <td colspan="2" class="border-0 p-5">
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr class="align-middle table-primary" noclick>
                                    <td colspan="2">Параллель: <strong><?= $item['parallelName'] ?></strong></td>
                                </tr>
                            <?php
                            }

                            if ($curGroupId !== $item['id']) {
                                $curGroupId = $item['id'];
                            ?>
                                <tr class="align-middle table-info" noclick>
                                    <th scope="row" class="fit text-end text-nowrap"><?= $indexGroup ?></th>
                                    <td colspan="2">Группа: <strong><?= $item['name'] ?></strong></td>
                                </tr>
                            <?php
                                $indexGroup++;
                            }
                            ?>
                            <tr class="align-middle" noclick>
                                <th scope="row" class="text-end text-nowrap"></th>
                                <td id="gr_<?= $item['id'] ?>"></td>
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
                        <p class="m-0">Не найдена ни одна группа.</p>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="alert alert-info rounded-0 my-3" role="alert">
                <div>
                    <p class="m-0">Не найден ни один учебный год.</p>
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
            select.addEventListener('change', (e) => {
                e.stopPropagation();
                window.location.assign(`teacher-group.php?schoolYearId=${e.target.value}`);
            });

            // for (const item of document.querySelectorAll('button[data-action="remove"]')) {
            //     item.addEventListener('click', (e) => {
            //         e.stopPropagation();
            //         window.location.assign(`lesson.php?id=${item.dataset.id}&action=remove&schoolYearId=<?= $schoolYearId ?>`);
            //     });
            // }

            // for (const item of document.querySelectorAll('button[data-action="edit"]')) {
            //     item.addEventListener('click', (e) => {
            //         e.stopPropagation();
            //         window.location.assign(`lesson.php?id=${item.dataset.id}&schoolYearId=<?= $schoolYearId ?>`);
            //     });
            // }

            // for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr:not([noclick])')) {
            //     item.addEventListener('click', (e) => {
            //         e.stopPropagation();
            //         window.location.assign(`lesson-journal.php?id=${item.dataset.id}&groupId=<?= $groupId ?>&schoolYearId=<?= $schoolYearId ?>`);
            //     });
            // }
        });
    </script>

</body>

</html>