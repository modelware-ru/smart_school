<?php

use MW\Shared\Constant;

// JS : i18n(langId, 'TTL_?')
// plain -> ${args[0]}
// html -> {args[0]}
$i18n_TTL = [
    'GROUP_NAME' => [
        '_type' => 'plain',
        'ru' => 'Название группы',
        'en' => 'Group name',
    ],
    'GROUP_PARALLEL' => [
        '_type' => 'plain',
        'ru' => 'Параллель группы',
        'en' => 'Group parallel',
    ],
    'GROUP_TEACHERS' => [
        '_type' => 'plain',
        'ru' => 'Преподаватели группы',
        'en' => 'Group teachers',
    ],
    'LOGIN' => [
        '_type' => 'plain',
        'ru' => 'Логин',
        'en' => 'Login',
    ],
    'PARALLEL_NUMBER' => [
        '_type' => 'plain',
        'ru' => 'Номер параллели',
        'en' => 'Parallel number',
    ],
    'PARALLEL_NAME' => [
        '_type' => 'plain',
        'ru' => 'Название параллели',
        'en' => 'Parallel name',
    ],
    'PARALLEL_SHOW_IN_GROUP' => [
        '_type' => 'plain',
        'ru' => 'Показать в группах',
        'en' => 'Show in group',
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
    'TO_CANCEL' => [
        '_type' => 'plain',
        'ru' => 'Отменить',
        'en' => 'Cancel',
    ],
    'TO_REMOVE' => [
        '_type' => 'plain',
        'ru' => 'Удалить',
        'en' => 'Remove',
    ],
    'TO_REMOVE_IN_PROGRESS' => [
        '_type' => 'plain',
        'ru' => 'Удаление...',
        'en' => 'Removing...',
    ],
    'TO_SAVE' => [
        '_type' => 'plain',
        'ru' => 'Сохранить',
        'en' => 'Save',
    ],
    'TO_SAVE_IN_PROGRESS' => [
        '_type' => 'plain',
        'ru' => 'Сохранение...',
        'en' => 'Saving...',
    ],
    'TO_SIGN_IN' => [
        '_type' => 'plain',
        'ru' => 'Войти',
        'en' => '',
    ],
    'TO_SIGN_IN_IN_PROGRESS' => [
        '_type' => 'plain',
        'ru' => 'Вход...',
        'en' => 'Signing In...',
    ],


    'SIGN_OUT' => [
        '_type' => 'plain',
        'ru' => 'Выход',
        'en' => 'Sign Out',
    ],
];

// JS ['ru', 'en']: i18n(langId, 'TTL_?')
// plain -> ${args[0]}
// html -> {args[0]}
// PHP ['log']
$i18n_MSG = [
    'FIELD_EMAIL_INCORRECT' => [
        '_type' => 'plain',
        'ru' => 'Некорректный адрес электронной почты',
        'en' => 'Email is incorrect',
        'log' => 'Некорректный адрес электронной почты: %s',
    ],
    'FIELD_IS_REQUIRED' => [
        '_type' => 'plain',
        'ru' => 'Поле должно быть заполнено',
        'en' => 'This is a required field',
        'log' => 'Поле должно быть заполнено: %s',
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
    'FIELD_WITH_DUPLICATED_VALUE' => [
        '_type' => 'plain',
        'ru' => 'Запись с таким значением уже существует',
        'en' => 'This is a duplicated value',
        'log' => 'Запись с таким значением уже существует: "%s"',
    ],
    'IMPOSSIBLE_TO_REMOVE_DATA' => [
        '_type' => 'plain',
        'ru' => 'Невозможно удалить данные: ${args[0]}',
        'en' => 'Impossible to remove data',
        'log' => 'Невозможно удалить данные: %s',
    ],
    'WRONG_FIELD_VALUE' => [
        '_type' => 'plain',
        'ru' => 'Неверное значение поля',
        'en' => 'Wrong value of the field',
        'log' => 'Неверное значение поля: %s - %s',
    ],
    'WRONG_LOGIN_OR_PASSWORD' => [
        '_type' => 'plain',
        'ru' => 'Логин или пароль не верны',
        'en' => 'Login or password are wrong',
        'log' => 'Попытка входа в систему. %s',
    ],

];

// JS ['ru', 'en']: i18n(langId, 'TTL_?')
// plain -> ${args[0]}
// html -> {args[0]}
// PHP ['log'] -> ThrowEx
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

