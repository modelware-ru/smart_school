<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;
use MW\Service\Authz\Constant as AuthzConstant;

global $templateData;
global $langId;
global $roleId;
global $userId;

$roleName = AuthzConstant::GetRoleName($roleId);

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'teacherId' => $userId,
];

list($res, $data) = (new DomainModule())->getGroupListForTeacher($args);

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
        <a href="schedule.php?id=<?= $item['id']?>" class="group-item group-item-schedule d-flex justify-content-center align-items-center rounded-4 position-relative">
            <span><?= $item['name'] ?></span>
            <span class="position-absolute top-0 end-0 me-2 fs-3 text-black-50"><i class="bi bi-table"></i></span>
        </a>
        <a href="student-list.php?id=<?= $item['id']?>" class="group-item group-item-person d-flex justify-content-center align-items-center rounded-4 position-relative">
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
</body>

</html>