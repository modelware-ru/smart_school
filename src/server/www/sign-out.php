<?php
require_once '../app/include.php';

use MW\Shared\Session;

Session::Instance()->reset();

$redirectUrl = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/index.php";
header("Location: {$redirectUrl}");
