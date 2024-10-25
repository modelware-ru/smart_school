<?php

use MW\Module\Domain\Student\Main as StudentModule;

function srv_save_student($args)
{
    list($res, $data) = (new StudentModule())->saveStudent($args);

    return $res;
}

function srv_remove_student($args)
{
    list($res, $data) = (new StudentModule())->removeStudent($args);

    return $res;
}

function srv_change_class($args)
{
    list($res, $data) = (new StudentModule())->changeClass($args);

    return $res;
}

function srv_change_group($args)
{
    list($res, $data) = (new StudentModule())->changeGroup($args);

    return $res;
}

function srv_save_student_solution($args)
{
    list($res, $data) = (new StudentModule())->saveStudentSolution($args);

    return $res;
}
