<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '.');

require_once 'vendor/autoload.php';

use MW\App\Setting;
use MW\Service\Authz\Main as AuthzService; 
use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use PHPUnit\Framework\TestCase;

define('PHPUNIT', true);
define('OUT_OF_SCOPE_ID', -1);

final class TestGetPermission extends TestCase
{
    //  N |                                      
    // -------------------------------------------
    //  1 | accountId не существует
    //  2 | roleId === null
    //  3 | roleId не существует
    //  4 | roleState === null
    //  5 | roleState не соответствует roleId
    //  6 | resourceType === null
    //  7 | resourceType не существует
    //  8 | resource === null                      
    //  9 | resource не cooтветствует resourceType
    // 10 | actionId === null
    // 11 | actionId не существует
    //
    // 12 | accountId === null - поиск по остальным корректным данным
    // 13 | accountId существует - поиск и по остальным корректным данным

    private static $_Log;

    const INPUT_TEST_DATA = [
        'test01' => [
            'userId' => OUT_OF_SCOPE_ID,
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test02' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test03' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test04' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test05' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test06' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test07' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test08' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test09' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test10' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test11' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test12' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
        ],
        'test13' => [
            'userId' => '',
            'roleId' => '',
            'roleState' => '',
            'resourceType' => '',
            'resource' => '',
            'actionId' => '',
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
