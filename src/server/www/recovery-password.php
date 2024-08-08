<?php
require_once '../app/include.php';
require_once 'app/init.php';

use MW\App\Page;
use MW\Service\Authz\Constant as AuthzConstant;
use MW\Shared\MWI18nHelper;

Page::Init(AuthzConstant::RESOURCE_PAGE_RECOVERY_PASSWORD, MWI18nHelper::PAGE_TITLE_RECOVERY_PASSWORD);
