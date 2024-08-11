<?php

use MW\Shared\Util;
use MW\Service\Authz\Constant as AuthzConstant;

function check_type_parameters($apiResource, $payload)
{
    static $checkList = [
        // signIn START
        AuthzConstant::RESOURCE_API_SIGN_IN => [
            '_type' => 'object',
            'login' => 'string',
            'password' => 'string',
        ],
        // signIn FINISH
        // saveParallel START
        AuthzConstant::RESOURCE_API_SAVE_PARALLEL => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'number' => 'string',
            'showInGroup' => true,
        ],
        // saveParallel FINISH
        // removeParallel START
        AuthzConstant::RESOURCE_API_REMOVE_PARALLEL => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeParallel FINISH
        // saveGroup START
        AuthzConstant::RESOURCE_API_SAVE_GROUP => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'parallelId' => 1,
        ],
        // saveGroup FINISH
        // removeGroup START
        AuthzConstant::RESOURCE_API_REMOVE_GROUP => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeGroup FINISH
    ];

    if (!array_key_exists($apiResource, $checkList)) {
        return false;
    }

    return Util::CompareStructures($payload, $checkList[$apiResource]);
}
