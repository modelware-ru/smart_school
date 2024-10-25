<?php

use MW\Module\Domain\CategoryTag\Main as CategoryTagModule;

function srv_save_categoryTag($args)
{
    list($res, $data) = (new CategoryTagModule())->saveCategoryTag($args);

    return $res;
}

function srv_remove_categoryTag($args)
{
    list($res, $data) = (new CategoryTagModule())->removeCategoryTag($args);

    return $res;
}
