<?php
echo '<?php' . PHP_EOL;
?>
// GENERATED [<?=date('Y-m-d H:i:s')?>]
namespace MW\Shared;

class MWI18nHelper
{
<?php
foreach ($i18n_PAGE_TITLE as $key => $value) {
?>
    const PAGE_TITLE_<?=$key?> = 'PAGE_TITLE_<?=$key?>';
<?php
}
?>

<?php
foreach ($i18n_MSG as $key => $value) {
    ?>
    const MSG_<?=$key?> = 'MSG_<?=$key?>';
<?php
}
?>

<?php
foreach ($i18n_ERR as $key => $value) {
    ?>
    const ERR_<?=$key?> = 'ERR_<?=$key?>';
<?php
}
?>

    private static ?MWI18nHelper $_Instance = null;
    private array $_pageTemplateList = [];
    private array $_msgList = [];
    private array $_errorMsgList = [];
    private array $_errorLogMsgList = [];
    private array $_errorHttpStatusList = [];

    public static function Instance(): MWI18nHelper
    {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new MWI18nHelper();
        }
        return self::$_Instance;
    }

    public static function LogMessage($code, $data): string
    {
        $message = (self::Instance())->message($code);
        if (is_null($message)) {
            return '';
        }
        
        return $message(...$data);
    }

    public function page($pageName, $langId): callable
    {
        return $this->_pageTemplateList[$pageName][$langId];
    }

    public function message($errCode): callable
    {
        return $this->_msgList[$errCode];
    }

    public function errorLogMessage($errCode): callable
    {
        return $this->_errorLogMsgList[$errCode];
    }

    public function httpStatus($errCode): int
    {
        return $this->_errorHttpStatusList[$errCode];
    }

    private function __construct()
    {
        $this->_pageTemplateList = [
<?php
foreach ($i18n_PAGE_TITLE as $key => $value) {
    ?>
            self::PAGE_TITLE_<?=$key?> => [
                'ru' =>
                    function (...$args) {
                        return sprintf('<?=$value['title']['ru']?>', ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf('<?=$value['title']['en']?>', ...$args);
                    },
                ],
<?php
}
?>
        ];
        $this->_msgList = [
<?php
foreach ($i18n_MSG as $key => $value) {
    ?>
            self::MSG_<?=$key?> => function (...$args) {
                return sprintf('<?=$value['log']?>', ...$args);
            },
<?php
}
?>
        ];

        $this->_errorMsgList = [
<?php
foreach ($i18n_ERR as $key => $value) {
    ?>
            self::ERR_<?=$key?> => [
                'ru' =>
                    function (...$args) {
                        return sprintf('<?=$value['ru']?>', ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf('<?=$value['en']?>', ...$args);
                    },
                ],
<?php
}
?>
        ];        
        $this->_errorLogMsgList = [
<?php
foreach ($i18n_ERR as $key => $value) {
    ?>
            self::ERR_<?=$key?> => function (...$args) {
                return sprintf('<?=$value['log']?>', ...$args);
            },
<?php
}
?>
        ];
        $this->_errorHttpStatusList = [
<?php
foreach ($i18n_ERR as $key => $value) {
    ?>
            self::ERR_<?=$key?> => <?=$value['httpStatus']?>,
<?php
}
?>
        ];
    }
}
