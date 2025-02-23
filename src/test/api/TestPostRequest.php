<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '../../server');
set_include_path(get_include_path() . PATH_SEPARATOR . '.');

require_once 'vendor/autoload.php';
require_once 'MWTestUtil.php';

use MW\Shared\Logger;
use MW\Shared\MWI18nHelper;
use PHPUnit\Framework\TestCase;

final class TestPostRequest extends TestCase
{
    private static $_Log;

    //  N | data                   |
    // -----------------------------
    //  1 | без 'data'             |
    //  2 | 'data' = null          |
    //  3 | 'data' = []            |
    //  4 | 'data' с полем 'field' |

    const INPUT_TEST_DATA = [
        'test01' => [
            'body' => [],
        ],
        'test02' => [
            'body' => [
                'data' => null,
            ],
        ],
        'test03' => [
            'body' => [
                'data' => [],
            ],
        ],
        'test04' => [
            'body' => [
                'data' => [
                    'field' => 1,
                ],
            ],
        ],
    ];

    public static function setUpBeforeClass(): void
    {
        self::$_Log = Logger::Init('test-api-post-request', false, 'path_localhost');
    }

    private static function _Fetch($key)
    {
        $body = self::INPUT_TEST_DATA[$key]['body'];
        return MWTestUtil::Fetch(self::$_Log, $key, $body);
    }
    public function test01(): void
    {
        self::$_Log->notice('start: test01');

        [$response, $error, $status, $request] = self::_Fetch(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 400);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'error');
        $this->assertEquals($responseArray['data']['code'], MWI18nHelper::ERR_WRONG_POST_PARAMETERS);
        $this->assertEmpty($responseArray['data']['args']);

        self::$_Log->notice('finish: test01');
    }

    public function test02(): void
    {
        self::$_Log->notice('start: test02');

        [$response, $error, $status, $request] = self::_Fetch(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 400);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'error');
        $this->assertEquals($responseArray['data']['code'], MWI18nHelper::ERR_WRONG_POST_PARAMETERS);
        $this->assertEmpty($responseArray['data']['args']);

        self::$_Log->notice('finish: test02');
    }

    public function test03(): void
    {
        self::$_Log->notice('start: test03');

        [$response, $error, $status, $request] = self::_Fetch(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 400);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'error');
        $this->assertEquals($responseArray['data']['code'], MWI18nHelper::ERR_WRONG_POST_PARAMETERS);
        $this->assertEmpty($responseArray['data']['args']);

        self::$_Log->notice('finish: test03');
    }

    public function test04(): void
    {
        self::$_Log->notice('start: test04');

        [$response, $error, $status, $request] = self::_Fetch(__FUNCTION__);

        $this->assertFalse($error);
        $this->assertEquals($status['http_code'], 400);
        $this->assertJson($response);

        $responseArray = json_decode($response, true);
        $this->assertEquals($responseArray['status'], 'error');
        $this->assertEquals($responseArray['data']['code'], MWI18nHelper::ERR_WRONG_POST_PARAMETERS);
        $this->assertEmpty($responseArray['data']['args']);

        self::$_Log->notice('finish: test04');
    }

}
