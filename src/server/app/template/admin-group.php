<?php

use MW\Shared\Util;
use MW\Module\Domain\Main as DomainModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$groupId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $groupId !== 0) ? $query['action'] : '';

if ($groupId === 0) {
    $group = [
        'id' => 0,
        'name' => '',
        'parallelId' => '0',
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'groupId' => $groupId,
    ];

    list($res, $data) = (new DomainModule())->getGroupById($args);

    $group = $res->getData();
    $group['parallelId'] = strval($group['parallelId']);
}

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];

list($res, $data) = (new DomainModule())->getParallelList($args);

$parallelList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => !$item['showInGroup'],
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите параллель",
        "disabled" => true,
    ]
]);

$args = [
    'permissionOptions' => $templateData['permissionOptions'],
    'groupId' => $groupId,
];

$templateData['_js']['group'] = $group;
$templateData['_js']['parallelList'] = $parallelList;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/admin_group_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="group-list.php">Список групп</a></li>
                    <?php if ($groupId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая группа</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Группа "<?= $group['name'] ?>"</span></li>
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