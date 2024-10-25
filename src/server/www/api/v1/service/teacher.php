<?php

use MW\Module\Domain\Teacher\Main as TeacherModule;

function srv_block_teacher($args)
{
    list($res, $data) = (new TeacherModule())->blockTeacher($args);

    return $res;
}

function srv_save_teacher($args)
{
    list($res, $data) = (new TeacherModule())->saveTeacher($args);

    return $res;
}

function srv_remove_teacher($args)
{
    list($res, $data) = (new TeacherModule())->removeTeacher($args);

    return $res;
}

function srv_save_teacherGroup($args)
{
    list($res, $data) = (new TeacherModule())->saveTeacherGroup($args);

    return $res;
}
