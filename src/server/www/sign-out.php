<?php
require_once './defines.php';
require_once PATH_TO_INCLUDE;

use MW\Shared\Session;

Session::Instance()->reset();

$redirectUrl = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php";
header("Location: {$redirectUrl}");
