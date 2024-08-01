<?php
namespace MW\Shared;

class Constant {

    // HTTP Status
    const HTTP_BAR_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401; // нужна аутентификация
    const HTTP_FORBIDDEN = 403; // нужна авторизация
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
}
