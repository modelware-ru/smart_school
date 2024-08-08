<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getParallelList($args);

?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
</head>

<body>
    <div class="container">
        <nav id="nav" class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/adminNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div id="main" class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Список параллелей</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex justify-content-end">
            <a href="parallel.php?id=0" class="btn btn-success">
                <i class="bi bi-plus-circle me-3"></i>
                <span role="status">Добавить</span>
            </a>
        </div>

        <?php
        $itemList = $res->getData();
        if ($res->isOk() && count($itemList) > 0) {
        ?>
            <table class="table table-hover table-bordered clickable-rows my-3">
                <thead>
                    <tr class="table-active border-dark-subtle">
                        <th scope="col" class="text-end fit">#</th>
                        <th scope="col">Название (текст)</th>
                        <th scope="col">Название (число)</th>
                        <th scope="col">Показать в группах</th>
                        <th scope="col" class="fit">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($res->getData() as $key => $item) {
                    ?>
                        <tr class="align-middle <?= $item['showInGroup'] ? "" : "table-danger" ?>" data-id="<?= $item['id'] ?>">
                            <th scope="row" class="text-end text-nowrap"><?= $key + 1 ?></th>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['number'] ?></td>
                            <td><?= $item['showInGroup'] ? "Да" : "Нет" ?></td>
                            <td class="p-1">
                                <?php if ($item['canBeDeleted']) { ?>
                                    <button data-action="remove" data-id="<?= $item['id'] ?>" class='btn btn-outline-danger btn-sm'><i class="bi bi-trash"></i></button>
                                <? } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                <?php
            } else {
                ?>
                    <div class="alert alert-info rounded-0 my-3" role="alert">
                        <div>
                            <p class="m-0">Не найдена ни одна параллель.</p>
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
                    window.location.assign(`parallel.php?id=${item.dataset.id}&action=remove`);
                });
            }

            for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`parallel.php?id=${item.dataset.id}`);
                });
            }
        });
    </script>
</body>

</html>