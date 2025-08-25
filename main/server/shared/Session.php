<?php
namespace MW\Shared;

class Session
{
    private static ?Session $_Instance = null;

    private function __construct()
    {
        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 31);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 31);
        ini_set('session.gc_divisor', 1000);
        session_name(SESSION_NAME);
        session_start();
//        session_regenerate_id();
    }

    public static function Start()
    {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new Session();
        }
    }

    public static function Instance()
    {
        self::Start();
        return self::$_Instance;
    }

    public function reset()
    {
        $_SESSION = [];
        unset($_COOKIE[session_name()]);
        session_destroy();
        self::$_Instance = null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

}
