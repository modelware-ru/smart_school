<?php

use MW\Shared\Constant;

// JS :
// plain -> ${args[0]}
// html -> {args[0]}
$i18n_TTL = [
    'LOGIN' => [
        '_type' => 'plain',
        'ru' => 'Логин',
        'en' => 'Login',
    ],
    'PASSWORD' => [
        '_type' => 'plain',
        'ru' => 'Пароль',
        'en' => 'Password',
    ],
    'RECOVERY_PASSWORD' => [
        '_type' => 'plain',
        'ru' => 'Восстановление пароля',
        'en' => 'Recovery Password',
    ],
    'SIGN_IN' => [
        '_type' => 'plain',
        'ru' => 'Вход',
        'en' => 'Sign In',
    ],
    'TO_AUTHENTICATE' => [
        '_type' => 'plain',
        'ru' => 'Аутентификация...',
        'en' => 'Authentication...',
    ],
    'TO_SIGN_IN' => [
        '_type' => 'plain',
        'ru' => 'Войти',
        'en' => '',
    ],


    'SIGN_OUT' => [
        '_type' => 'plain',
        'ru' => 'Выход',
        'en' => 'Sign Out',
    ],
];

$i18n_MSG = [
    'FIELD_EMAIL_INCORRECT' => [
        '_type' => 'plain',
        'ru' => 'Некорректный адрес электронной почты',
        'en' => 'Email is incorrect',
        'log' => 'Некорректный адрес электронной почты: %s',
    ],
    'FIELD_IS_TOO_LONG' => [
        '_type' => 'plain',
        'ru' => 'Поле содержит слишком длинное значение',
        'en' => 'The field is too long',
        'log' => 'Поле содержит слишком длинное значение: %d больше чем %d',
    ],
    'FIELD_IS_TOO_SHORT' => [
        '_type' => 'plain',
        'ru' => 'Поле содержит слишком короткое значение',
        'en' => 'The field is too short',
        'log' => 'Поле содержит слишком короткое значение: %d меньше чем %d',
    ],
    'FIELD_IS_REQUIRED' => [
        '_type' => 'plain',
        'ru' => 'Поле должно быть заполнено',
        'en' => 'This is a required field',
        'log' => 'Поле должно быть заполнено: %s',
    ],
    'WRONG_EMAIL_OR_PASSWORD' => [
        '_type' => 'plain',
        'ru' => 'Email или пароль не верен',
        'en' => 'Email or password are wrong',
        'log' => 'Попытка входа в систему. Не верный %s',
    ],
];

$i18n_ERR = [
    'UNKNOWN' => [
        '_type' => 'plain',
        'ru' => 'Неизвестная ошибка',
        'en' => 'Unknown error',
        'log' => 'Неизвестная ошибка: %s',
        'httpStatus' => Constant::HTTP_INTERNAL_SERVER_ERROR,
    ],
    'AUTHORIZATION_NEEDED' => [
        '_type' => 'plain',
        'ru' => 'Нет прав для выполнения данной операции',
        'en' => 'Access forbidden',
        'log' => 'Нет прав для выполнения данной операции' . PHP_EOL . 'RoleId:%d' . PHP_EOL . 'Resource:%s' . PHP_EOL . 'ActionId:%d' . PHP_EOL,
        'httpStatus' => Constant::HTTP_FORBIDDEN,
    ],
    'DB_CONNECTION_FAILED' => [
        '_type' => 'plain',
        'ru' => 'Нет доступа к базе данных',
        'en' => 'Database connection failed',
        'log' => 'Нет доступа к базе данных',
        'httpStatus' => Constant::HTTP_INTERNAL_SERVER_ERROR,
    ],
    'DB_SQL_STATEMENT_FAILED' => [
        '_type' => 'plain',
        'ru' => 'Ошибка при выполнении запроса к базе данных',
        'en' => 'SQL statement error',
        'log' => 'Ошибка при выполнении запроса к базе данных' . PHP_EOL . 'Exception: %s' . PHP_EOL . 'SQL: %s' . PHP_EOL . 'vars: %s' . PHP_EOL . 'constVars: %s' . PHP_EOL,
        'httpStatus' => Constant::HTTP_INTERNAL_SERVER_ERROR,
    ],
    'WRONG_REQUEST_PARAMETERS' => [
        '_type' => 'plain',
        'ru' => 'Неверные параметры запроса',
        'en' => 'Wrong request parameters',
        'log' => 'Неправильные типы данных в запросе: %s\n%s',
        'httpStatus' => Constant::HTTP_BAR_REQUEST,
    ],
];

