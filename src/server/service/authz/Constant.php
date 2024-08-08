<?php
// GENERATED [2024-08-03 20:09:35]
namespace MW\Service\Authz;

class Constant {

    // Permission
    const PERMISSION_ALLOW = 'ALLOW';
    const PERMISSION_PROHIBIT = 'PROHIBIT';

    // Role ID
    const ROLE_GUEST_ID = 1; // Гость
    const ROLE_ADMIN_ID = 2; // Администратор
    const ROLE_TEACHER_ID = 3; // Преподаватель
    // Role State
    const ROLE_STATE_GUEST_ACTIVE_ID = 1; // Гость : Активный
    const ROLE_STATE_ADMIN_ACTIVE_ID = 2; // Администратор : Активный
    const ROLE_STATE_TEACHER_ACTIVE_ID = 3; // Преподаватель : Активный
    const ROLE_STATE_TEACHER_BLOCKED_ID = 4; // Преподаватель : Заблокированный

    const ROLE_STATE = [
        self::ROLE_GUEST_ID => [
            self::ROLE_STATE_GUEST_ACTIVE_ID,
        ],
        self::ROLE_ADMIN_ID => [
            self::ROLE_STATE_ADMIN_ACTIVE_ID,
        ],
        self::ROLE_TEACHER_ID => [
            self::ROLE_STATE_TEACHER_ACTIVE_ID,
            self::ROLE_STATE_TEACHER_BLOCKED_ID,
        ],
    ];

    // Resource Type
    const RESOURCE_TYPE_API = 'main__api'; 
    const RESOURCE_TYPE_PAGE = 'main__page'; 
    const RESOURCE_TYPE_WIDGET = 'main__widget'; 

    // Resources
    // API
    const RESOURCE_API_SIGN_IN = 'signIn'; 
    const RESOURCE_API_RECOVERY_PASSWORD = 'recoveryPassword'; 
    const RESOURCE_API_SIGN_OUT = 'signOut'; 
    const RESOURCE_API_SAVE_PARALLEL = 'saveParallel'; 
    const RESOURCE_API_SAVE_GROUP = 'saveGroup'; 
    const RESOURCE_API_SAVE_TEACHER = 'saveTeacher'; 
    const RESOURCE_API_REMOVE_PARALLEL = 'removeParallel'; 
    const RESOURCE_API_REMOVE_GROUP = 'removeGroup'; 
    const RESOURCE_API_BLOCK_TEACHER = 'blockTeacher'; 
    // PAGE
    const RESOURCE_PAGE_GUEST_INDEX = 'guestIndex'; 
    const RESOURCE_PAGE_RECOVERY_PASSWORD = 'recoveryPassword'; 
    const RESOURCE_PAGE_MESSAGE = 'message'; 
    const RESOURCE_PAGE_ADMIN_INDEX = 'adminIndex'; 
    const RESOURCE_PAGE_PARALLEL = 'parallel'; 
    const RESOURCE_PAGE_PARALLEL_LIST = 'parallelList'; 
    const RESOURCE_PAGE_GROUP = 'group'; 
    const RESOURCE_PAGE_GROUP_LIST = 'groupList'; 
    const RESOURCE_PAGE_TEACHER = 'teacher'; 
    const RESOURCE_PAGE_TEACHER_LIST = 'teacherList'; 
    const RESOURCE_PAGE_TEACHER_INDEX = 'teacherIndex'; 
    // WIDGET

    // Action
    const ACTION_API_CALL = 1; // Вызов метода
    const ACTION_PAGE_SHOW = 2; // Показ страницы
    const ACTION_WIDGET_SHOW = 3; // Показ виджета

    public static function CheckRoleStateId($roleId, $roleStateId) {
        return in_array($roleStateId, self::ROLE_STATE[$roleId]);
    }
}