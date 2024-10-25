<?php

use MW\Module\Domain\Parallel\Main as ParallelModule;

function srv_save_parallel($args)
{
    list($res, $data) = (new ParallelModule())->saveParallel($args);

    return $res;
}

function srv_remove_parallel($args)
{
    list($res, $data) = (new ParallelModule())->removeParallel($args);

    return $res;
}
