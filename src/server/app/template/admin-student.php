<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$studentId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $studentId !== 0) ? $query['action'] : '';

if ($studentId === 0) {
    $student = [
        'id' => 0,
        'firstName' => '',
        'lastName' => '',
        'middleName' => '',
    ];

    $studentName = '';

} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'studentId' => $studentId,
    ];

    list($res, $data) = (new DomainModule())->getStudentById($args);

    $student = $res->getData();

    $studentName = "{$student['lastName']} {$student['firstName']} {$student['middleName']}";

}
$templateData['_js']['student'] = $student;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_student_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="student-list.php">Список учеников</a></li>
                    <?php if ($studentId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новый ученик</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Ученик "<?= $studentName ?>"</span></li>
                    <?php } ?>
                </ol>
            </nav>
        </div>
        <div id="main" class="d-flex flex-column">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>