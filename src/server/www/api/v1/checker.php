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
    ];

    if (!array_key_exists($apiResource, $checkList)) {
        return false;
    }

    return Util::CompareStructures($payload, $checkList[$apiResource]);
}
