<?php
namespace MW\Shared;

use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;
use MW\App\Setting;

class Logger
{
    private static array $_LogList = [];
    private static string $_DefaultName = '';

    public static function Init(string $name, bool $defaultName = false, string $pathLogTag = 'path')
    {

        if ($defaultName || empty(self::$_DefaultName)) {
            self::$_DefaultName = $name;
        }

        if (key_exists($name, self::$_LogList)) {
            return self::$_LogList[$name];
        }

        self::$_LogList[$name] = new MonologLogger($name);

        $formatter = new LineFormatter(
            format: "[%datetime%] %level_name%.%channel%: %message% %context% %extra%\n",
            dateFormat: 'Y-m-d\TH:i:s',
            allowInlineLineBreaks: true,
            ignoreEmptyContextAndExtra: true,
        );

        $settings = Setting::Get('log');

        if (array_key_exists($name, $settings['logger'])) {
            $log = $settings['logger'][$name];
            $maxFiles = $log['maxFiles'];
            $level = $log['level'];
        } else {
            $maxFiles = $settings['maxFiles'];
            $level = $settings['level'];
        }

        $handler = new RotatingFileHandler(
            filename: $settings[$pathLogTag] . DIRECTORY_SEPARATOR . $name . '.log',
            maxFiles: $maxFiles,
            level: $level);
        $handler->setFormatter($formatter);
        self::$_LogList[$name]->pushHandler($handler);
        self::$_LogList[$name]->pushProcessor(function ($entry) {
            global $requestUID;
            // if (
            //     isset($entry['context']) &&
            //     isset($entry['context']['payload']) &&
            //     isset($entry['context']['payload']['email'])
            // ) {
            //     $entry['context']['payload']['email'] = '**************';
            // }
            $entry['extra']['uid'] = $requestUID;
            return $entry;
        });

        ErrorHandler::register(self::$_LogList[$name]);

        return self::$_LogList[$name];
    }

    public static function Log(string $name = null)
    {
        return empty($name) ? self::$_LogList[self::$_DefaultName] : self::$_LogList[$name];
    }
}
