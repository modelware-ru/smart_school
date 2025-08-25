<?php

use MW\Shared\Util;

define('PHPUNIT', true);

class MWTestUtil
{

    const URL = 'http://localhost:8888/api/v1/request.php';

    public static function Fetch($log, $key, $body)
    {

        [$response, $error, $status, $request] = Util::Fetch('post', self::URL, [
            'body' => json_encode($body),
        ]);

        $log->notice($key, [
            'response' => print_r(json_decode($response, true), true),
            'error' => $error,
            'status' => $status,
            'request' => $request,
        ]);
        return [$response, $error, $status, $request];
    }

}
