<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$resource = $templateData['resource'];
$query = Util::HandleGET();

$studentId = isset($query['id']) ? intval($query['id']) : 0;

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentId' => $studentId,
];

list($res, $data) = (new DomainModule())->getStudentById($args);

$student = $res->getData();

$studentName = "{$student['lastName']} {$student['firstName']} {$student['middleName']}";

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentId' => $studentId,
];

list($res, $data) = (new DomainModule())->getStudentClassGroupHistory($args);

?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
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
                    <li class="breadcrumb-item"><a href="student-list.php">Список учеников</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold"> История ученика "<?= $studentName ?>"</span></li>
                </ol>
            </nav>
        </div>
        <?php
        $history = $res->getData();
        if ($res->isOk() && count($history) > 0) {
        ?>
            <table class="table table-hover table-bordered my-3">
                <thead>
                    <tr class="table-active border-dark-subtle">
                        <th scope="col" class="text-end fit">#</th>
                        <th scope="col">Дата</th>
                        <th scope="col" class="text-center">Класс</th>
                        <th scope="col">Группа</th>
                        <th scope="col">Причина</th>
                        <th scope="col" class="fit">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($res->getData() as $key => $item) {
                    ?>
                        <tr class="align-middle">
                            <th scope="row" class="text-end text-nowrap"><?= $key + 1 ?></th>
                            <td><?= $item['startDate'] ?></td>
                            <td class="text-center"><?= $item['className'] ?></td>
                            <td><?= $item['groupName'] ?></td>
                            <td><?= $item['reason'] ?></td>
                            <td class="p-1">
                                <?php
                                if ($item['classHistoryId'] !== 0) {
                                ?>
                                    <button data-action="removeClassHistory" data-id="<?= $item['classHistoryId'] ?>" class='btn btn-outline-danger btn-sm'><i class="bi bi-trash"></i></button>
                                <?php
                                } else if ($item['groupHistoryId'] !== 0) {
                                ?>
                                    <button data-action="removeGroupHistory" data-id="<?= $item['groupHistoryId'] ?>" class='btn btn-outline-danger btn-sm'><i class="bi bi-trash"></i></button>
                                <?php
                                }
                                ?>
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
                    <p class="m-0">Не найдена ни одна запись в истории.</p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            for (const item of document.querySelectorAll('button[data-action="removeClassHistory"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('item.dataset.action', item.dataset.action);
                    console.log('item.dataset.id', item.dataset.id);
                    // window.location.assign(`student.php?id=${item.dataset.id}&action=remove`);
                });
            }
            for (const item of document.querySelectorAll('button[data-action="removeGroupHistory"]')) {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('item.dataset.action', item.dataset.action);
                    console.log('item.dataset.id', item.dataset.id);
                    // window.location.assign(`student.php?id=${item.dataset.id}&action=remove`);
                });
            }
        });
    </script>
</body>

</html>