<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;
use MW\Service\Authz\Constant as AuthzConstant;

global $templateData;
global $langId;

$resource = $templateData['resource'];

$query = Util::HandleGET();

$teacherId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $teacherId !== 0) ? $query['action'] : '';

if ($teacherId === 0) {
    $teacher = [
        'id' => 0,
        'firstName' => '',
        'lastName' => '',
        'middleName' => '',
        'roleStateId' => '0',
        'login' => '',
        'password' => '',
        'email' => '',
    ];

    $teacherName = '';
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'teacherId' => $teacherId,
    ];

    list($res, $data) = (new DomainModule())->getTeacherById($args);

    $teacher = $res->getData();
    $teacher['roleStateId'] = strval($teacher['roleStateId']);

    $teacherName = "{$teacher['lastName']} {$teacher['firstName']} {$teacher['middleName']}";
}

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

$roleStateList = [
    0 => [
        "value" => "0",
        "name" => "Выберите статус",
        "disabled" => true,
    ],
    1 => [
        'value' => strval(AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID),
        'name' => 'Активный',
        'disabled' => false,
    ],
    2 => [
        'value' => strval(AuthzConstant::ROLE_STATE_TEACHER_BLOCKED_ID),
        'name' => 'Заблокированный',
        'disabled' => false,
    ]
];

list($res, $data) = (new DomainModule())->getGroupList($args);

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

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'teacherId' => $teacherId,
];

list($res, $data) = (new DomainModule())->getGroupListForTeacher($args);

$groupListForTeacher = array_map(function ($item) {
    return [
        'id' => strval($item['id']),
        'name' => "{$item['name']} [{$item['parallelName']}]",
    ];
}, $res->getData());

$templateData['_js']['teacher'] = $teacher;
$templateData['_js']['roleStateList'] = $roleStateList;
$templateData['_js']['groupList'] = $groupList;
$templateData['_js']['groupListForTeacher'] = $groupListForTeacher;
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
            <?= Util::RenderTemplate('app/template/shared/adminNavigator.php') ?>
        </nav>
        <hr class='m-0' />
        <div class="my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Меню</a></li>
                    <li class="breadcrumb-item"><a href="teacher-list.php">Список преподавателей</a></li>
                    <?php if ($teacherId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новый преподаватель</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Преподаватель "<?= $teacherName ?>"</span></li>
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