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
