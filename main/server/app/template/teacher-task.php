<?php

use MW\Shared\Util;
use MW\Module\Domain\Task\Main as TaskModule;
use MW\Module\Domain\Topic\Main as TopicModule;

global $templateData;
global $langId;

$query = Util::HandleGET();

$taskId = isset($query['id']) ? intval($query['id']) : 0;
$action = (isset($query['action']) && $topicId !== 0) ? $query['action'] : '';

if ($taskId === 0) {
    $task = [
        'id' => 0,
        'name' => '',
        'topicSubtopicList' => []
    ];
} else {
    $args = [
        'permissionOptions' => $templateData['permissionOptions'],
        'taskId' => $taskId,
    ];

    list($res, $data) = (new TaskModule())->getTaskById($args);

    $task = $res->getData();


    $task['topicSubtopicList'] = array_map(function ($item) {
        return [
            'name' => "{$item['topicName']} / {$item['subtopicName']}",
            'first' => strval($item['topicId']),
            'second' => strval($item['subtopicId']),
        ];
    }, $task['topicSubtopicList']);
}

// getTopicList
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];
list($res, $data) = (new TopicModule())->getTopicList($args);

$topicList = array_reduce($res->getData(), function ($carry, $item) {
    $carry[] = [
        'value' => strval($item['id']),
        'name' => $item['name'],
        'disabled' => false,
    ];
    return $carry;
}, [
    0 => [
        "value" => "0",
        "name" => "Выберите тему",
        "disabled" => true,
    ]
]);


// getTopicSubtopicList
$args = [
    'permissionOptions' => $templateData['permissionOptions'],
];
list($res, $data) = (new TopicModule())->getTopicSubtopicList($args);


$topicSubtopicList = [
    0 =>  [
        0 => [
            "value" => "0",
            "name" => "Тема не выбрана",
            "disabled" => true,
        ]
    ]
];

foreach ($res->getData() as $item) {
    $topicSubtopicList[$item['id']] = array_reduce($item['subtopicList'], function ($carry, $item) {
        $carry[] = [
            'value' => strval($item['id']),
            'name' => $item['name'],
            'disabled' => false,
        ];
        return $carry;
    }, [
        0 => [
            "value" => "0",
            "name" => "Выберите подтему",
            "disabled" => true,
        ]
    ]);
}


$templateData['_js']['task'] = $task;
$templateData['_js']['topicList'] = $topicList;
$templateData['_js']['topicSubtopicList'] = $topicSubtopicList;
$templateData['_js']['action'] = $action;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/teacher_task_bundle.js' defer></script>
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
                    <li class="breadcrumb-item"><a href="task-list.php">Список задач</a></li>
                    <?php if ($taskId === 0) { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Новая задача</span></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item active" aria-current="page"><span class="fw-bold">Задача "<?= $task['name'] ?>"</span></li>
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