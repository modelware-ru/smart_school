<?php

namespace MW\Module\Domain;

use MW\App\Setting;
use MW\Service\Authz\Constant as AuthzConstant;
use MW\Service\Authz\Main as AuthzService;
use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;
use MW\Shared\ValueChecker;

class Main
{
    public function getParallelList($args)
    {
        $localLog = Logger::Log()->withName('Module::Domain::getParallelList');
        $localLog->info('parameters:', Util::MaskData($args));

        $permissionOptions = $args['permissionOptions'];

        // test. start
        if (defined('PHPUNIT')) {
        }
        // test. finish

        // check. start
        // check. finish

        $manager = new Manager();
        $resDb = $manager->getParallelList();

        $res = array_map(function ($item) {
            return [
                'id' => $item['parallel_id'],
                'nameText' => $item['name_text'],
                'nameNumber' => $item['name_number'],
                'showInGroup' => $item['show_in_group'],
                'canBeDeleted' => ($item['mg_count'] + $item['msch_count']) === 0,
            ];
        }, $resDb);

        return [Util::MakeSuccessOperationResult($res), []];
    }
}
