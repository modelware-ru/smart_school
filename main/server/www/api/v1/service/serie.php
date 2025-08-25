<?php

use MW\Module\Domain\Serie\Main as SerieModule;

function srv_save_serie($args)
{
    list($res, $data) = (new SerieModule())->saveSerie($args);

    return $res;
}

function srv_remove_serie($args)
{
    list($res, $data) = (new SerieModule())->removeSerie($args);

    return $res;
}

function srv_add_home_serie_to_student($args)
{
    list($res, $data) = (new SerieModule())->addHomeSerieToStudent($args);

    return $res;
}

function srv_remove_home_serie_from_student($args)
{
    list($res, $data) = (new SerieModule())->removeHomeSerieFromStudent($args);

    return $res;
}
