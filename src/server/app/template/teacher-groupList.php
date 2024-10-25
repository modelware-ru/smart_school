<?php

use MW\Shared\Util;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;
use MW\Module\Domain\Teacher\Main as TeacherModule;
use MW\Service\Authz\Constant as AuthzConstant;

global $templateData;
global $langId;
global $roleId;
global $userId;

$roleName = AuthzConstant::GetRoleName($roleId);

$query = Util::HandleGET();

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
        $schoolYearId = intval($item['id']);
    }

    $schoolYearList[] = [
        'id' => strval($item['id']),
        'title' => $item['name'] . ' &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;' . $item['startDate'] . '&nbsp;&nbsp;-&nbsp;&nbsp;' . $item['finishDate'],
        'isCurrent' => $item['isCurrent'],
        'isSelected' => $isSelected,
    ];
}

if ($schoolYearId === 0 && count($schoolYearList) > 0) {
    $schoolYearId = intval($schoolYearList[0]['id']);
}

// getGroupListForTeacher
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'teacherId' => $userId,
    'schoolYearId' => $schoolYearId,
];

list($res, $data) = (new TeacherModule())->getGroupListForTeacher($args);

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
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Список групп по параллелям</span></li>
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
        }
        ?>
        <div>
            <?php
            $itemList = $res->getData();
            $curParallel = '';
            if ($res->isOk() && count($itemList) > 0) {
                foreach ($itemList as $key => $item) {
                    if ($curParallel !== $item['parallelName']) {
                        $curParallel = $item['parallelName'];
            ?>
        </div>
        <p class="mt-5">Параллель: <span class="fw-bold"><?= $item['parallelName'] ?></span></p>
        <div class="d-flex flex-wrap gap-3">
        <?php
                    }
        ?>
        <a href="schedule.php?id=<?= $item['id'] ?>&schoolYearId=<?= $schoolYearId?>" class="group-item group-item-schedule d-flex justify-content-center align-items-center rounded-4 position-relative">
            <span><?= $item['name'] ?></span>
            <span class="position-absolute top-0 end-0 me-2 fs-3 text-black-50"><i class="bi bi-table"></i></span>
        </a>
        <a href="student-list.php?id=<?= $item['id'] ?>&schoolYearId=<?= $schoolYearId?>" class="group-item group-item-person d-flex justify-content-center align-items-center rounded-4 position-relative">
            <span><?= $item['name'] ?></span>
            <span class="position-absolute top-0 end-0 me-2 fs-3 text-black-50"><i class="bi bi-person"></i></span>
        </a>

    <?
                }
    ?>
        </div>
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
    ?>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('selectSchoolYear');
            select.addEventListener('change', (e) => {
                e.stopPropagation();
                window.location.assign(`group-list.php?schoolYearId=${e.target.value}`);
            });
        });
    </script>
</body>

</html>