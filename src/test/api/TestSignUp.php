<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '.');

require_once 'vendor/autoload.php';
require_once 'MWTestUtil.php';

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use PHPUnit\Framework\TestCase;

final class TestSignUp extends TestCase
{
    //  N | signUp Code   |
    // --------------------
    //  1 | плохой hash   |
    //  2 | пустой        |
    //  3 | короткий      |
    //  4 | длинный       |
    //  5 | неправильный  |
    //  6 | правильный    |

    private static $_Log;

    const ABOUT_ME = 'about me';
    const EMAIL = 'valid-email@email.ru';
    const NEW_ACCOUNT_ID = 2;
    const NEW_USER_ID = 1;

    private static $_Code;
    private static $_Hash;

    public static function setUpBeforeClass(): void
    {
        self::$_Log = Logger::Init('test-api-signUp', false, 'path_localhost');
        [$response, $error, $status, $request] = self::_GetCode();

        $responseArray = json_decode($response, true);
        self::$_Code = $responseArray['test']['code'];
        self::$_Hash = $responseArray['test']['hash'];
    }

    private static function _GetCode()
    {
        $body = [
            'resource' => 'getCode',
            'payload' => [
                'aboutMe' => self::ABOUT_ME,
                'email' => self::EMAIL,
            ]];

        return MWTestUtil::Fetch(self::$_Log, 'getCode', $body);
    }

    private static function _SignUp($aboutMe, $email, $code)
    {
        $body = [
            'resource' => 'signUp',
            'payload' => [
                'aboutMe' => $aboutMe,
                'email' => $email,
                'code' => $code,
            ]];

        return MWTestUtil::Fetch(self::$_Log, 'signUp', $body);
    }

    public function test01(): void
    {
        self::$_Log->notice('start: test01');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME . '+', self::EMAIL . '+', '0000');

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['code']['code'], MWI18nHelper::MSG_FIELD_CODE_INCORRECT);

        self::$_Log->notice('finish: test01');
    }

    public function test02(): void
    {
        self::$_Log->notice('start: test02');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME, self::EMAIL, '');

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['code']['code'], MWI18nHelper::MSG_FIELD_IS_REQUIRED);

        self::$_Log->notice('finish: test02');
    }

    public function test03(): void
    {
        self::$_Log->notice('start: test03');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME, self::EMAIL, substr(self::$_Code, 0, -1));

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['code']['code'], MWI18nHelper::MSG_FIELD_CODE_INCORRECT);

        self::$_Log->notice('finish: test03');
    }

    public function test04(): void
    {
        self::$_Log->notice('start: test04');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME, self::EMAIL, self::$_Code . '1');

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['code']['code'], MWI18nHelper::MSG_FIELD_CODE_INCORRECT);

        self::$_Log->notice('finish: test04');
    }

    public function test05(): void
    {
        self::$_Log->notice('start: test05');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME, self::EMAIL, '0000');

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'fail');
        $this->assertEquals($responseArray['data']['code']['code'], MWI18nHelper::MSG_FIELD_CODE_INCORRECT);

        self::$_Log->notice('finish: test05');
    }

    public function test06(): void
    {
        self::$_Log->notice('start: test06');

        [$response, $error, $status, $request] = self::_SignUp(self::ABOUT_ME, self::EMAIL, self::$_Code);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 200);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'ok');
        $this->assertArrayNotHasKey('code', $responseArray['data']);
        $this->assertEquals($responseArray['test']['accountId'], self::NEW_ACCOUNT_ID);
        $this->assertEquals($responseArray['test']['userId'], self::NEW_USER_ID);

        self::$_Log->notice('finish: test06');
    }
}
