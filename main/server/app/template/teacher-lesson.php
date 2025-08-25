<?php

use MW\Shared\Util;
use MW\Module\Domain\Serie\Main as SerieModule;
use MW\Module\Domain\Group\Main as GroupModule;
use MW\Module\Domain\Teacher\Main as TeacherModule;
use MW\Module\Domain\Lesson\Main as LessonModule;
use MW\Module\Domain\Subject\Main as SubjectModule;
use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;

global $templateData;
global $langId;
global $userId;

$query = Util::HandleGET();

$lessonId = isset($query['id']) ? intval($query['id']) : 0;
$groupId = isset($query['groupId']) ? intval($query['groupId']) : 0;
$schoolYearId = isset($query['schoolYearId']) ? intval($query['schoolYearId']) : 0;
$startDate = null;
$finishDate = null;
$action = (isset($query['action']) && $lessonId !== 0) ? $query['action'] : '';

if ($lessonId === 0) {
    $lesson = [
        'id' => 0,
        'date' => '',
        'groupId' => strval($groupId),
        'subjectId' => '0',
    ];
} else {
    // getLessonById
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'lessonId' => $lessonId,
    ];

    list($res, $data) = (new LessonModule())->getLessonById($args);

    $lesson = $res->getData();
    $groupId = $lesson['groupId'];
    $lesson['groupId'] = strval($lesson['groupId']);
    $lesson['subjectId'] = strval($lesson['subjectId']);
}

$lesson['callbackQuery'] = "id={$groupId}&schoolYearId={$schoolYearId}";

// getSchoolYearById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'schoolYearId' => $schoolYearId,
];

list($res, $data) = (new SchoolYearModule())->getSchoolYearById($args);

$schoolYearDb = $res->getData();

$startDate = $schoolYearDb['startDate'];
$finishDate = $schoolYearDb['finishDate'];


// getSubjectList
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new SubjectModule())->getSubjectList($args);

$subjectList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите предмет",
        "disabled" => true,
    ]
]);

// getGroupListForTeacher
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'teacherId' => $userId,
    'schoolYearId' => $schoolYearId,
];

list($res, $data) = (new TeacherModule())->getGroupListForTeacher($args);

$groupList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => "{$item['name']} [{$item['parallelName']}]",
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите группу",
        "disabled" => true,
    ]
]);


// getSerieList
list($res, $data) = (new SerieModule())->getSerieList($args);

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
        "disabled" => true,
    ]
]);

// getSerieListInLesson
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new SerieModule())->getSerieListInLesson($args);

$serieListInLesson = array_map(function ($item) {
    return [
        'id' => strval($item['id']),
        'name' => $item['name'],
    ];
}, $res->getData());

// getGroupById
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
];

list($res, $data) = (new GroupModule())->getGroupById($args);

$groupName = ($res->getData())['name'];
$parallelName = ($res->getData())['parallelName'];


$templateData['_js']['lesson'] = $lesson;
$templateData['_js']['date'] = ['startDate' => $startDate, 'finishDate' => $finishDate];
$templateData['_js']['subjectList'] = $subjectList;
$templateData['_js']['groupList'] = $groupList;
$templateData['_js']['serieList'] = $serieList;
$templateData['_js']['serieListInLesson'] = $serieListInLesson;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_lesson_bundle.js' defer></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/teacher-navigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="schedule.php?id=<?= $groupId ?>&schoolYearId=<?= $schoolYearId ?>">Расписание группы "<?= $groupName ?>" [<?= $parallelName ?>]</a></li>
                    <?php if ($lessonId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новое занятие</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Занятие</span></li>
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