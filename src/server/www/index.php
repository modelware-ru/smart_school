<?php
require_once '../app/include.php';
require_once 'app/init.php';

use MW\App\Page;
use MW\Service\Authz\Constant as AuthzConstant;
use MW\Shared\MWI18nHelper;

global $roleId;
switch ($roleId) {
    case AuthzConstant::ROLE_GUEST_ID:
        $resource = AuthzConstant::RESOURCE_PAGE_GUEST_INDEX;
        $title = MWI18nHelper::PAGE_TITLE_GUEST_INDEX;
        break;
    case AuthzConstant::ROLE_ADMIN_ID:
        $resource = AuthzConstant::RESOURCE_PAGE_ADMIN_INDEX;
        $title = MWI18nHelper::PAGE_TITLE_ADMIN_INDEX;
        break;
    case AuthzConstant::ROLE_TEACHER_ID:
        $resource = AuthzConstant::RESOURCE_PAGE_TEACHER_INDEX;
        $title = MWI18nHelper::PAGE_TITLE_TEACHER_INDEX;
        break;
}

Page::Init($resource, $title);
