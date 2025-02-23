<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '.');

require_once 'vendor/autoload.php';
require_once 'MWTestUtil.php';

use MW\App\Setting;
use MW\Module\Account\Main as AccountModule;
use MW\Module\Account\Manager as AccountModule_Manager;
use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use PHPUnit\Framework\TestCase;

define('ABOUT_ME_MAX_LENGTH', AccountModule::ABOUT_ME_MAX_LENGTH);
define('LONG_ABOUT_ME', str_repeat('X', ABOUT_ME_MAX_LENGTH + 1));

final class TestGetCode extends TestCase
{
    //  N | aboutMe       | email        |
    // -----------------------------------
    //  1 | пустой        | пустой       |
    //  2 | пустой        | неправильный |
    //  3 | пустой        | правильный   |
    //  4 | неправильный  | пустой       |
    //  5 | неправильный  | неправильный |
    //  6 | неправильный  | правильный   |
    //  7 | правильный    | пустой       |
    //  8 | правильный    | неправильный |
    //  9 | правильный    | правильный   |
    // 10 | правильный    | правильный   | повтор (обновление записи)
    // 11 | правильный    | правильный   | повтор (expiry дата)

    private static $_Log;

    const INPUT_TEST_DATA = [
        'test01' => [
            'aboutMe' => '',
            'email' => '',
        ],
        'test02' => [
            'aboutMe' => '',
            'email' => 'not-valid-email',
        ],
        'test03' => [
            'aboutMe' => '',
            'email' => 'valid-email@email.ru',
        ],
        'test04' => [
            'aboutMe' => LONG_ABOUT_ME,
            'email' => '',
        ],
        'test05' => [
            'aboutMe' => LONG_ABOUT_ME,
            'email' => 'not-valid-email',
        ],
        'test06' => [
            'aboutMe' => LONG_ABOUT_ME,
            'email' => 'valid-email@email.ru',
        ],
        'test07' => [
            'aboutMe' => 'about me',
            'email' => '',
        ],
        'test08' => [
            'aboutMe' => 'about me',
            'email' => 'not-valid-email',
        ],
        'test09' => [
            'aboutMe' => 'about me',
            'email' => 'valid-email@email.ru',
        ],
        'test10' => [
            'aboutMe' => 'about me',
            'email' => 'valid-email@email.ru',
        ],
        'test11' => [
            'aboutMe' => 'about me',
            'email' => 'valid-email@email.ru',
        ],
    ];

    public static function setUpBeforeClass(): void
    {
        self::$_Log = Logger::Init('test-api-getCode', false, 'path_localhost');
    }

    private static function _GetCode($key)
    {
        $aboutMe = self::INPUT_TEST_DATA[$key]['aboutMe'];
        $email = self::INPUT_TEST_DATA[$key]['email'];
        $body = [
            'resource' => 'getCode',
            'payload' => [
                'aboutMe' => $aboutMe,
                'email' => $email,
            ]];

        return MWTestUtil::Fetch(self::$_Log, $key, $body);
    }

    public function test01(): void
    {
        self::$_Log->notice('start: test01');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);

        self::$_Log->notice('finish: test01');
    }

    public function test02(): void
    {
        self::$_Log->notice('start: test02');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_EMAIL_INCORRECT);

        self::$_Log->notice('finish: test02');
    }

    public function test03(): void
    {
        self::$_Log->notice('start: test03');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);
        $this->assertArrayNotHasKey('email', $responseArray['data']);

        self::$_Log->notice('finish: test03');
    }

    public function test04(): void
    {
        self::$_Log->notice('start: test04');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_TOO_LONG);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);

        self::$_Log->notice('finish: test04');
    }

    public function test05(): void
    {
        self::$_Log->notice('start: test05');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_TOO_LONG);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_EMAIL_INCORRECT);

        self::$_Log->notice('finish: test05');
    }

    public function test06(): void
    {
        self::$_Log->notice('start: test06');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['aboutMe']['code'], MWI18nHelper::MSG_FIELD_IS_TOO_LONG);
        $this->assertArrayNotHasKey('email', $responseArray['data']);

        self::$_Log->notice('finish: test06');
    }

    public function test07(): void
    {
        self::$_Log->notice('start: test07');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertArrayNotHasKey('aboutMe', $responseArray['data']);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);

        self::$_Log->notice('finish: test07');
    }

    public function test08(): void
    {
        self::$_Log->notice('start: test08');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertArrayNotHasKey('aboutMe', $responseArray['data']);
        $this->assertEquals($responseArray['data']['email']['code'], MWI18nHelper::MSG_FIELD_EMAIL_INCORRECT);

        self::$_Log->notice('finish: test08');
    }

    public function test09(): void
    {
        self::$_Log->notice('start: test09');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'ok');
        $this->assertArrayNotHasKey('aboutMe', $responseArray['data']);
        $this->assertArrayNotHasKey('mail', $responseArray['data']);

        $manager = new AccountModule_Manager('localhost', false);
        $now = date('Y-m-d H:i:s', time());
        $rowDb = $manager->getSignUpCodeByHash($responseArray['test']['hash'], $now);

        $this->assertEquals(count($rowDb), 1);
        $this->assertEquals($rowDb[0]['code'], $responseArray['test']['code']);
        self::$_Log->notice('finish: test09');
    }

    public function test10(): void
    {
        self::$_Log->notice('start: test10');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'ok');
        $this->assertArrayNotHasKey('aboutMe', $responseArray['data']);
        $this->assertArrayNotHasKey('mail', $responseArray['data']);

        $manager = new AccountModule_Manager('localhost', false);
        $now = date('Y-m-d H:i:s', time());
        $rowDb = $manager->getSignUpCodeByHash($responseArray['test']['hash'], $now);

        $this->assertEquals(count($rowDb), 1);
        $this->assertEquals($rowDb[0]['code'], $responseArray['test']['code']);
        self::$_Log->notice('finish: test10');
    }

    public function test11(): void
    {
        self::$_Log->notice('start: test11');

        [$response, $error, $status, $request] = self::_GetCode(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'ok');
        $this->assertArrayNotHasKey('aboutMe', $responseArray['data']);
        $this->assertArrayNotHasKey('mail', $responseArray['data']);

        $manager = new AccountModule_Manager('localhost');
        $now = date('Y-m-d H:i:s', time());
        $regCodeExpiryTime = Setting::Get('app.account.codeExpiryTime');
        $now = date('Y-m-d H:i:s', strtotime("+ {$regCodeExpiryTime} minute", time()));

        $rowDb = $manager->getSignUpCodeByHash($responseArray['test']['hash'], $now);

        $this->assertEquals(count($rowDb), 0);
        self::$_Log->notice('finish: test11');
    }

}
