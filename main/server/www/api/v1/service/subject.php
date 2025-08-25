<?php

use MW\Module\Domain\Subject\Main as SubjectModule;


function srv_save_subject($args)
{
    list($res, $data) = (new SubjectModule())->saveSubject($args);

    return $res;
}

function srv_remove_subject($args)
{
    list($res, $data) = (new SubjectModule())->removeSubject($args);

    return $res;
}
