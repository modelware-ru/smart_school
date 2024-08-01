<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/share/nginx/www');
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/share/nginx');

define('SESSION_NAME', 'smart-school');

require_once 'vendor/autoload.php';

