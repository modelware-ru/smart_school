<?php

use MW\Shared\Util;
use MW\Module\Domain\Student\Main as StudentModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$studentSerieId = isset($query['id']) ? intval($query['id']) : 0;

// getStudentSerieById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentSerieId' => $studentSerieId,
];

list($res, $data) = (new StudentModule())->getStudentSerieById($args);
$studentSerie = $res->getData();

// getStudentSolutionById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'studentSerieId' => $studentSerieId,
];

list($res, $data) = (new StudentModule())->getStudentSolutionById($args);

$studentSolution = $res->getData();

$templateData['_js']['studentSerieId'] = $studentSerieId;

?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_studentSerieSolution_bundle.js' defer></script>
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
                    <td><?= $studentSerie['lastName'] ?> <?= $studentSerie['firstName'] ?> <?= $studentSerie['middleName'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Параллель</th>
                    <td><?= $studentSerie['parallelName'] ?> [<?= $studentSerie['parallelNumber'] ?>]</td>
                </tr>
                <tr>
                    <th scope="row">Группа</th>
                    <td><?= $studentSerie['groupName'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Серия</th>
                    <td><?= $studentSerie['serieName'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Тип серии</th>
                    <td><?= $studentSerie['serieType'] === 'HOME' ? "Домашняя" : "Классная" ?> / <?= $studentSerie['serieDate'] ?></td>
                </tr>
                <?php if (!is_null($studentSerie['subjectName'])) { ?>
                    <tr>
                        <th scope="row">Занятие</th>
                        <td><?= $studentSerie['subjectName'] ?> / <?= $studentSerie['lessonDate'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <table class="table table-primary table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Задача</th>
                    <th scope="col">Оценка</th>
                    <th scope="col">Дата</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 0;
                foreach ($studentSolution as $solution) {
                ?>
                    <tr>
                        <th scope="row"><?= ++$index ?></th>
                        <td><?= $solution['taskName'] ?></td>
                        <td>
                            <input type="text" value="<?= $solution['solutionValue'] ?>" size="4" data-solutionid=<?= $solution['solutionId'] ?> data-serietaskid=<?= $solution['serieTaskId'] ?> />
                        </td>
                        <td><?= $solution['solutionDate'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div id="main" class="d-flex flex-column">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>