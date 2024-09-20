<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;
global $userId;

$resource = $templateData['resource'];

$query = Util::HandleGET();

$lessonId = isset($query['id']) ? intval($query['id']) : 0;
$groupId = isset($query['groupId']) ? intval($query['groupId']) : 0;
$schoolYearId = isset($query['schoolYearId']) ? intval($query['schoolYearId']) : -1;
$action = (isset($query['action']) && $lessonId !== 0) ? $query['action'] : '';

if ($lessonId === 0) {
    $lesson = [
        'id' => 0,
        'date' => '',
        'groupId' => strval($groupId),
        'subjectId' => '0',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'lessonId' => $lessonId,
    ];

    list($res, $data) = (new DomainModule())->getLessonById($args);

    $lesson = $res->getData();
}

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getSubjectList($args);

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

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'teacherId' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getGroupListForTeacher($args);

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





list($res, $data) = (new DomainModule())->getActiveTeacherList($args);

$activeTeacherList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите преподавателя",
        "disabled" => true,
    ]
]);

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'lessonId' => $lessonId,
];

list($res, $data) = (new DomainModule())->getTeacherListInGroup($args);

$teacherListInGroup = array_map(function ($item) {
    return [
        'id' => strval($item['id']),
        'name' => "{$item['lastName']} {$item['firstName']} {$item['middleName']}",
    ];
}, $res->getData());

$templateData['_js']['lesson'] = $lesson;
$templateData['_js']['parallelList'] = $parallelList;
$templateData['_js']['activeTeacherList'] = $activeTeacherList;
$templateData['_js']['teacherListInGroup'] = $teacherListInGroup;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/<?= $resource ?>_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="schedule.php?id=<?= $groupId ?>&schoolYearId==<?= $schoolYearId ?>">Расписание группы</a></li>
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