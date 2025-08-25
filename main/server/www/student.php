<?php
require_once './defines.php';
require_once PATH_TO_INCLUDE;
require_once 'app/init.php';

use MW\App\Page;
use MW\Service\Authz\Constant as AuthzConstant;
use MW\Shared\MWI18nHelper;

Page::Init(AuthzConstant::RESOURCE_PAGE_STUDENT, MWI18nHelper::PAGE_TITLE_STUDENT);
