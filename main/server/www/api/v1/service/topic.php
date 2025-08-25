<?php

use MW\Module\Domain\Topic\Main as TopicModule;

function srv_save_topic($args)
{
    list($res, $data) = (new TopicModule())->saveTopic($args);

    return $res;
}

function srv_remove_topic($args)
{
    list($res, $data) = (new TopicModule())->removeTopic($args);

    return $res;
}
