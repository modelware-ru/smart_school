<?php

use MW\Module\Domain\SchoolYear\Main as SchoolYearModule;

function srv_save_schoolYear($args)
{
    list($res, $data) = (new SchoolYearModule())->saveSchoolYear($args);

    return $res;
}

function srv_remove_schoolYear($args)
{
    list($res, $data) = (new SchoolYearModule())->removeSchoolYear($args);

    return $res;
}
