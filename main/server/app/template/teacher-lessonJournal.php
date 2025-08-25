<?php

use MW\Shared\Util;
use MW\Module\Domain\Serie\Main as SerieModule;
use MW\Module\Domain\Group\Main as GroupModule;
use MW\Module\Domain\Lesson\Main as LessonModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$lessonId = isset($query['id']) ? intval($query['id']) : 0;
$schoolYearId = isset($query['schoolYearId']) ? intval($query['schoolYearId']) : 0;

// getStudentListForLesson
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new LessonModule())->getStudentListForLesson($args);

$studentList = array_map(function ($item) {
    return [
        'id' => $item['id'],
        'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
        'note' => $item['note'],
        'attendanceDictId' => $item['attendanceDictId'],
        'serieList' => $item['serieList'],
    ];
}, $res->getData());


// getSerieListInLesson
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new SerieModule())->getSerieListInLesson($args);

$serieList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите серию",
        "disabled" => false,
    ]
]);

// getAttendanceDict
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new LessonModule())->getAttendanceDict($args);

$attendanceDict = array_map(function ($item) {
    return [
        'id' => $item['id'],
        'name' => $item['name'],
        'display' => $item['display'],
        'default' => $item['default'],
    ];
}, $res->getData());

// getGroupByLessonId
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new GroupModule())->getGroupByLessonId($args);

$groupId = ($res->getData())['id'];
$groupName = ($res->getData())['name'];
$parallelName  = ($res->getData())['parallelName'];

// getLessonById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new LessonModule())->getLessonById($args);

$lessonDate = ($res->getData())['date'];
$subjectName  = ($res->getData())['subjectName'];

$templateData['_js']['studentList'] = $studentList;
$templateData['_js']['serieList'] = $serieList;
$templateData['_js']['lessonId'] = $lessonId;
$templateData['_js']['groupId'] = $groupId;
$templateData['_js']['schoolYearId'] = $schoolYearId;
$templateData['_js']['attendanceDict'] = $attendanceDict;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_lessonJournal_bundle.js' defer></script>
</head>

<body>
    <div id="main" class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/teacher-navigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="group-list.php">Список групп по параллелям</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="schedule.php?id=<?= $groupId ?>&schoolYearId=<?= $schoolYearId ?>">Расписание группы "<?= $groupName ?>" [<?= $parallelName ?>]</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">[<?= $lessonDate ?>] <?= $subjectName ?></span></li>
                </ol>
            </nav>
        </div>
        <div id='student-group-list-header' class="d-flex justify-content-sm-end gap-1 gap-md-3 flex-wrap">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>