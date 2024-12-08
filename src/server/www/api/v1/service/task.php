<?php

use MW\Module\Domain\Task\Main as TaskModule;

function srv_save_task($args)
{
    list($res, $data) = (new TaskModule())->saveTask($args);

    return $res;
}

function srv_remove_task($args)
{
    list($res, $data) = (new TaskModule())->removeTask($args);

    return $res;
}
