<?php
namespace MW\App;

use Monolog\Level;
use MW\Service\Authz\Constant as AuthzConstant;

class Setting
{
    private const _DATA = [
        'database' => [
            'default' => 'main',
            'test-postfix' => '-test',
            'main' => [
                'dsn' => 'mysql:host=db;port=3306;dbname=smart_school;charset=UTF8',
                'user' => 'denis',
                'password' => 'denis',
            ],
            // 'main-test' => [
            //     'dsn' => 'mysql:host=host.docker.internal;port=3306;dbname=webapp-test;charset=UTF8',
            //     'user' => 'webapp',
            //     'password' => 'Webapp_1973!',
            // ],
            'localhost' => [
                'dsn' => 'mysql:host=127.0.0.1;port=23306;dbname=smart_school;charset=UTF8',
                'user' => 'denis',
                'password' => 'denis',
            ],
            // 'localhost-test' => [
            //     'dsn' => 'mysql:host=localhost;port=3306;dbname=webapp-test;charset=UTF8',
            //     'user' => 'webapp',
            //     'password' => 'Webapp_1973!',
            // ],            
        ],
        'app' => [
            // язык по умолчанию
            'defaultLangId' => 'ru',
            // доступные языки
            'availableLangIdList' => [
                'ru',
                'en',
            ],
            // модуль: account
            'account' => [
                // 'соль' для хеширования паролей
                'passSecret' => 'xd<z-WO3=qCqZ>:7T',
            ],
        ],
        'authz' => [
            // ALLOW -> Все, что не запрещено - разрешено
            // PROHIBIT -> Все, что не разрешено - запрещено
            'defaultPolicy' => [
                'main__api' => AuthzConstant::PERMISSION_PROHIBIT,
                'main__page' => AuthzConstant::PERMISSION_PROHIBIT,
                'main__widget' => AuthzConstant::PERMISSION_ALLOW,
            ],
            // роль по умолчанию при отсутствии аккаунта
            'defaultRoleId' => AuthzConstant::ROLE_GUEST_ID,
            // состояние роли по умолчанию при отсутствии аккаунта
            'defaultRoleStateId' => AuthzConstant::ROLE_STATE_GUEST_ACTIVE_ID,
        ],
        'log' => [
            'path_localhost' => '/home/denis/data/repo/github-modelware/smart_school/docker/app-log',
            'path' => '/var/log/app',
            'maxFiles' => 1,
            'level' => Level::Debug,
            'logger' => [
                'api-request' => [
                    'maxFiles' => 1,
                    'level' => Level::Debug,
                ],
            ],
        ],
        'test' => [
            'api' => [
                'host' => 'http://localhost:8888',
                'path' => '/api/v1/request.php',
            ],
        ],
    ];

    public static function Get(string $path): mixed
    {
        $pathList = explode('.', $path);
        if (empty($pathList)) {
            return null;
        }

        $pointer = self::_DATA;
        foreach ($pathList as $value) {
            if (!isset($pointer[$value])) {
                return null;
            }

            $pointer = $pointer[$value];
        }
        return $pointer;
    }
}