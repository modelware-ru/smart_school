<?php

use MW\Shared\Util;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;

global $templateData;
global $langId;

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new SchoolYearModule())->getSchoolYearList($args);

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
                    <li class="breadcrumb-item active" aria-current="page">Список учебных годов</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex justify-content-end">
            <a href="schoolyear.php?id=0" class="btn btn-success">
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
                        <th scope="col">Учебный год</th>
                        <th scope="col">Начало</th>
                        <th scope="col">Конец</th>
                        <th scope="col" class="fit">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($res->getData() as $key => $item) {
                        $tdClass = $item['isCurrent'] ? 'bg-primary-subtle' : '';
                    ?>
                        <tr class="align-middle" data-id="<?= $item['id'] ?>">
                            <th scope="row" class="text-end text-nowrap <?= $tdClass ?>"><?= $key + 1 ?></th>
                            <td class="<?= $tdClass ?>"><?= $item['name'] ?></td>
                            <td class="<?= $tdClass ?>"><?= $item['startDate'] ?></td>
                            <td class="<?= $tdClass ?>"><?= $item['finishDate'] ?></td>
                            <td class="p-1 <?= $tdClass ?>">
                                <?php if ($item['canBeRemoved']) { ?>
                                    <button data-action="remove" data-id="<?= $item['id'] ?>" class='btn btn-outline-danger btn-sm'><i class="bi bi-trash"></i></button>
                                <? } ?>
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
            for (const item of document.querySelectorAll('button[data-action="remove"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`schoolyear.php?id=${item.dataset.id}&action=remove`);
                });
            }

            for (const item of document.querySelectorAll('.table.clickable-rows>tbody>tr')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    window.location.assign(`schoolyear.php?id=${item.dataset.id}`);
                });
            }
        });
    </script>
</body>

</html>