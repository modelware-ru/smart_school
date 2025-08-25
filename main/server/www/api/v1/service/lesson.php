<?php

use MW\Module\Domain\Lesson\Main as LessonModule;

function srv_save_lesson($args)
{
    list($res, $data) = (new LessonModule())->saveLesson($args);

    return $res;
}

function srv_remove_lesson($args)
{
    list($res, $data) = (new LessonModule())->removeLesson($args);

    return $res;
}

function srv_add_serie_to_lesson($args)
{
    list($res, $data) = (new LessonModule())->addSerieToLesson($args);

    return $res;
}

function srv_remove_serie_from_lesson($args)
{
    list($res, $data) = (new LessonModule())->removeSerieFromLesson($args);

    return $res;
}