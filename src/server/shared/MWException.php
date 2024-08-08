<?php
namespace MW\Shared;

class MWException extends \Exception

{
    private string $_errCode;
    private array $_errData;
    private array $_logData;

    public function __construct(string $errCode, array $errData = [], array $logData = [])
    {
        $this->_errCode = $errCode;
        $this->_errData = $errData;
        $this->_logData = $logData;
    }

    public function errCode(): string
    {
        return $this->_errCode;
    }

    public function errData(): array
    {
        return $this->_errData;
    }

    public function logData(): array
    {
        return $this->_logData;
    }

    public function logMessage(): string
    {
        $errorLogMessage = (MWI18nHelper::Instance())->errorLogMessage($this->_errCode);
        if (is_null($errorLogMessage)) {
            return '';
        }
        
        return $errorLogMessage(...$this->_logData);
    }
    
    public function httpStatus(): int
    {
        return (MWI18nHelper::Instance())->httpStatus($this->_errCode);
    }

    public static function DefaultExceptionHandler(\Throwable $ex)
    {
        // $localLog = LogHelper::log()->withName('ExceptionHelper::handler()');
        // $localLog->addDebug('start');

        $error_msg = "{$ex->getMessage()} in {$ex->getFile()} @ {$ex->getLine()}";
        $error_trace = "{$ex->getTraceAsString()}";
        // $localLog->addError($error_msg);
        // $localLog->addError($error_trace);

        // $template_data = [
        //     'datetime' => DateHelper::dateUnixToString(time()) . ' (UTC)',
        //     'message' => $error_msg,
        //     'trace' => $error_trace,
        // ];
        // $settings = SettingManager::instance()->get('mail');
        // MailHelper::sendMail($settings['report'], 'Exception:' . $ex->getMessage(), processTemplate('template/mail/exception.php'));

        // $localLog->addDebug('finish');
        exit(1);
    }

    public static function ThrowEx(string $errCode, array $errData = [], array $logData = [])
    {
        throw new MWException($errCode, $errData, $logData);
    }
}
