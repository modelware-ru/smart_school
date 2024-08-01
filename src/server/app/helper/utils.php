<?php
function processTemplate($templateName)
{
    ob_start();
    require "$templateName";
    $res = ob_get_contents();
    ob_end_clean();
    return $res;
}

function jsonHeader($urls = [], $code = '')
{
    $localLog = LogHelper::log()->withName('jsonHeader()');
    $localLog->addDebug('start', ['urls' => $urls, 'code' => $code, 'HTTP_ORIGIN' => isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'undefined']);

    $result = true;
    if ($code === 'accept') {
        header('Content-Type: application/json;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
    } else {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'undefined';
        header('Content-Type: application/json;charset=utf-8');
        if (in_array($origin, $urls)) {
            header('Access-Control-Allow-Origin: $origin');
            header('Access-Control-Allow-Credentials: true');
        } else if (!empty($urls)){
            $result = false;
        }
    }
    return $result;
}

function fetch($method, $url, $settings = [])
{
    $localLog = LogHelper::log()->withName('fetch()');
    $localLog->addDebug('start', ['method' => $method, 'url' => $url, 'settings' => $settings]);

    // defaults
    $settings['body'] = isset($settings['body']) ? $settings['body'] : '';

    $request = [];
    $request['method'] = $method;
    $request['body'] = $settings['body'];

    $ch = curl_init();

    if (isset($settings['query'])) {
        $url = $url . '?' . $settings['query'];
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $request['url'] = $url;

    if ($method === 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings['body']);
    }

    if ($method === 'put') {
        curl_setopt($ch, CURLOPT_PUT, 1);
    }

    if ($method === 'delete') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    if (isset($settings['ipResolve'])) {
        if ($settings['ipResolve'] === 'ipv4') {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        if ($settings['ipResolve'] === 'ipv6') {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        }
    }

    if (isset($settings['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $settings['headers']);
        $request['header'] = $settings['headers'];
    }

    if (isset($settings['userpwd'])) {
        curl_setopt($ch, CURLOPT_USERPWD, $settings['userpwd']);
    }

    if (isset($settings['upload'])) {
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        curl_setopt($ch, CURLOPT_INFILESIZE, $settings['upload']['size']);
        curl_setopt($ch, CURLOPT_INFILE, $settings['upload']['file']);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $file = NULL;
    if (isset($settings['file'])) {
        $file = @fopen($settings['file'], 'w');
        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch);

    curl_close($ch);

    if (isset($settings['file'])) {
        fclose($file);
    }

    $localLog->addDebug('finish', ['request' => $request, 'response' => $response, 'error' => $error, 'status' => $status]);
    return [$response, (empty($error) ? FALSE : $error), $status, $request];
}

function getUserIp()
{
    $result = 'unknown';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $result = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $result = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $result = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $result = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $result = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $result = $_SERVER['REMOTE_ADDR'];
    }

    return $result;
}

function generateToken($key)
{
    return md5(uniqid($key, true));
}

function removeEmptyKeys(&$array)
{
    foreach ($array as $key => $value) {
        if (!isset($array[$key])) {
            unset($array[$key]);
        }
    }
}

function generateDigitCode($length = 4)
{
    return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function checkDataStructure($test, $template, $options = [])
{
    if (!isset($options['checkMissed'])) $options['checkMissed'] = true;
    if (!isset($options['checkNull'])) $options['checkNull'] = true;

//    echo "-----------------------\n";
//    var_dump($test);
//    var_dump($template);
//    echo "-----------------------\n";
    if (gettype($template) === 'array') {

        if (gettype($test) !== 'array') return FALSE;

        if ($template['_type'] === 'object') {

            $template_keys = array_keys($template);
            $test_keys = array_keys($test);

            if ($options['checkMissed'] && count($template_keys) - 1 !== count($test_keys)) return FALSE;

            foreach ($template_keys as $key) {
                if ($key === '_type') continue;
//                // both are NULL
//                if (!isset($test[$key]) && !isset($template[$key])) return TRUE;

                // no such key
                if ($options['checkNull'] && !isset($test[$key])) return FALSE;
                if (!isset($template[$key])) return FALSE;
                if (checkDataStructure($test[$key], $template[$key], $options) === FALSE) return FALSE;
            }

            return TRUE;
        } else if ($template['_type'] === 'array') {
            $keyType = $template['_keyType'];
            $itemTemplate = $template['_itemTemplate'];
            foreach ($test as $key => $item) {
                if (gettype($key) !== gettype($keyType)) return FALSE;
                if (checkDataStructure($item, $itemTemplate, $options) === FALSE) return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    } else {
        if (gettype($test) === 'NULL' && !$options['checkNull']) return TRUE;
        return gettype($template) === gettype($test);
    }

}

function checkDataStructureAndSendEmail($response, $template, $subject, $options = [])
{
    global $template_data;

    if (!checkDataStructure($response, $template, $options)) {
        $template_data = [
            'response' => print_r($response, TRUE),
            'template' => print_r($template, TRUE),
        ];

        $settings = SettingManager::instance()->get('mail');
        MailHelper::sendMail($settings['report'], $subject, processTemplate('template/mail/externalApiError.php'));
        return false;
    }

    return true;

}

function saveState($page, $pageState, $merge = true)
{
    $state = SessionManager::GetInstance()->getValueByKey($page);
    if (!empty($state)) {
        $newState = $merge ? array_merge($state, $pageState) : $pageState;
    } else {
        $newState = $pageState;
    }
    SessionManager::GetInstance()->setValueByKey($page, $newState);
}

function calculateAge($birthday) {
    $birthday_timestamp = strtotime($birthday);
    $age = date('Y') - date('Y', $birthday_timestamp);
    if (date('md', $birthday_timestamp) > date('md')) {
        $age--;
    }
    return $age;
}

/**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */
function number2string($num)
{
    $nul = 'ноль';
    $ten = array(
        array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять')
    );
    $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
    $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
    $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
    $unit = array(
        array('копейка' , 'копейки',   'копеек',     1),
        array('рубль',    'рубля',     'рублей',     0),
        array('тысяча',   'тысячи',    'тысяч',      1),
        array('миллион',  'миллиона',  'миллионов',  0),
        array('миллиард', 'миллиарда', 'миллиардов', 0),
    );

    list($rub, $kop) = explode('.', sprintf('%015.2f', floatval($num)));
    $out = array();
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) {
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1;
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; // 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
            else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
        }
    } else {
        $out[] = $nul;
    }
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
    $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

/**
 * Склоняем словоформу
 * @author runcore
 */
function morph($n, $f1, $f2, $f5)
{
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) return $f5;
    $n = $n % 10;
    if ($n > 1 && $n < 5) return $f2;
    if ($n == 1) return $f1;
    return $f5;
}

function emptyOrDate($date) {
    return empty($date) ? '' : $date;
}

function calcFullDates($start_date) {
    $fullDate1 = '';
    $fullDate2 = '';

    define('ONE_DAY_SECS', 24 * 60 * 60);
    $nowDateTime = strtotime('now');
    $firstSectionTime = strtotime($start_date);

    $fullDate1 = date('Y-m-d', $nowDateTime + 5 * ONE_DAY_SECS);
    $fullDate2 = date('Y-m-d', $firstSectionTime - 7 * ONE_DAY_SECS);

    if ($nowDateTime + 2 * ONE_DAY_SECS >= $firstSectionTime) {
        $fullDate1 = date('Y-m-d', $nowDateTime);
        $fullDate2 = date('Y-m-d', $nowDateTime);
    } else if ($nowDateTime + 7 * ONE_DAY_SECS >= $firstSectionTime) {
        $fullDate1 = date('Y-m-d', $nowDateTime + ONE_DAY_SECS);
        $fullDate2 = date('Y-m-d', $nowDateTime + ONE_DAY_SECS);
    } else if ($nowDateTime + 14 * ONE_DAY_SECS >= $firstSectionTime) {
        $fullDate1 = date('Y-m-d', $firstSectionTime - 7 * ONE_DAY_SECS);
        $fullDate2 = date('Y-m-d', $firstSectionTime - 7 * ONE_DAY_SECS);
    }
    
    return [$fullDate1, $fullDate2];
}

function dateToString($date, $type = 0) {
    static $months = [
        1 => ['январь', 'января'],
        2 => ['февраль', 'февраля'],
        3 => ['март', 'марта'],
        4 => ['апрель', 'апреля'],
        5 => ['май', 'мая'],
        6 => ['июнь', 'июня'],
        7 => ['июль', 'июля'],
        8 => ['август', 'августа'],
        9 => ['сентябрь', 'сентября'],
        10 => ['октябрь', 'октября'],
        11 => ['ноябрь', 'ноября'],
        12 => ['декабрь', 'декабря'],
    ];

    if (empty($date)) return '';

    $parsedStartDate = date_parse($date);
    return "{$parsedStartDate['day']} {$months[$parsedStartDate['month']][$type]} {$parsedStartDate['year']} года";
}