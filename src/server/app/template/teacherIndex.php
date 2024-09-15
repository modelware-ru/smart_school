<?php

use MW\Shared\Util;

global $templateData;
global $langId;

$resource = $templateData['resource'];
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/teacherNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Меню</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap gap-3">
                <a href="group-list.php" class="menu-item d-flex justify-content-center align-items-center rounded-4">
                    <span>Группы (NA)</span>
                </a>

                <a href="student-list.php" class="menu-item d-flex justify-content-center align-items-center rounded-4">
                    <span>Задачи (NA)</span>
                </a>

                <a href="serie-list.php" class="menu-item d-flex justify-content-center align-items-center rounded-4">
                    <span>Серии</span>
                </a>

                <a href="topic-list.php" class="menu-item d-flex justify-content-center align-items-center rounded-4">
                    <span>Темы задач</span>
                </a>

                <a href="category-tag-list.php" class="menu-item d-flex justify-content-center align-items-center rounded-4">
                    <span>Категории и<br/>теги</span>
                </a>

            </div>
        </div>
    </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>