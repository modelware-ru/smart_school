<?php

use MW\Module\Domain\Group\Main as GroupModule;

function srv_save_group($args)
{
    list($res, $data) = (new GroupModule())->saveGroup($args);

    return $res;
}

function srv_remove_group($args)
{
    list($res, $data) = (new GroupModule())->removeGroup($args);

    return $res;
}
