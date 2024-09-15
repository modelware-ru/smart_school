<?php
namespace MW\Shared;

use MW\App\Setting;

class DBManager
{
    private static $_ManagerList = [];

    private $_dsn;
    private $_user;
    private $_password;
    private $_driverOpts = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
    ];

    private $_pdo;
    private $_hasTransaction;

    public static function GetConnection(string $key = null, $hasTransaction = true)
    {
        if (is_null($key)) {
            $key = Setting::Get('database.default');
        }

        if (defined('PHPUNIT')) {
            $key = $key . Setting::Get('database.test-postfix');
        }

        if (!isset(self::$_ManagerList[$key])) {

            $settings = Setting::Get("database.{$key}");

            self::$_ManagerList[$key] = new DBManager();

            self::$_ManagerList[$key]->_dsn = $settings['dsn'];
            self::$_ManagerList[$key]->_user = $settings['user'];
            self::$_ManagerList[$key]->_password = $settings['password'];

            if (substr(self::$_ManagerList[$key]->_dsn, 0, 6) === 'sqlsrv') {
                unset(self::$_ManagerList[$key]->_driverOpts[\PDO::ATTR_ERRMODE]);
            }

            self::$_ManagerList[$key]->_pdo = new \PDO(self::$_ManagerList[$key]->_dsn, self::$_ManagerList[$key]->_user, self::$_ManagerList[$key]->_password, self::$_ManagerList[$key]->_driverOpts);
            if ($hasTransaction) {
                self::$_ManagerList[$key]->_pdo->beginTransaction();
            }
            self::$_ManagerList[$key]->_hasTransaction = $hasTransaction;
        }

        return self::$_ManagerList[$key];
    }

    public static function Commit(array $keys = [])
    {
        if (empty($keys)) {
            $keys = array_keys(self::$_ManagerList);
        }

        foreach ($keys as $key) {
            if (self::$_ManagerList[$key]->_hasTransaction) {
                self::$_ManagerList[$key]->_pdo->commit();
            }
        }
    }

    public static function Rollback(array $keys = [])
    {
        if (empty($keys)) {
            $keys = array_keys(self::$_ManagerList);
        }

        foreach ($keys as $key) {
            if (self::$_ManagerList[$key]->_hasTransaction) {
                self::$_ManagerList[$key]->_pdo->rollback();
            }
        }
    }

    public function select(string $stmt, array $vars = [])
    {
        try {
            $q = $this->_pdo->prepare($stmt);
            $q->execute($vars);
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), '-'],
            );
        }

        return $q->fetchAll();
    }

    public function insert(string $stmt, array $vars = [], array $constVars = [], bool $skipDuplicateError = false)
    {
        $ids = [];
        try {
            $q = $this->_pdo->prepare($stmt);

            foreach ($vars as $index => $var) {
                if ($index === 0) {

                    foreach ($constVars as $k => $v) {
                        $q->bindValue(':' . $k, $v);
                    }

                    $data = [];
                    $i = 0;
                    foreach ($vars[0] as $k => $v) {
                        $data[$i] = $v;
                        $q->bindParam(':' . $k, $data[$i]);
                        $i++;
                    }
                } else {
                    $i = 0;
                    foreach ($var as $v) {
                        $data[$i++] = $v;
                    }
                }

                try {
                    $q->execute();
                } catch (\PDOException $e) {
                    // TODO: Неправильная проверка на duplicate
                    if ($e->getCode() == '23000' && $skipDuplicateError) {
                        continue;
                    } else {
                        throw $e;
                    }
                }

                $ids[] = $this->_pdo->lastInsertId();
            }
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), print_r($constVars, true)],
            );
        }
        return $ids;
    }

    public function delete(string $stmt, array $vars = [])
    {
        try {
            $q = $this->_pdo->prepare($stmt);
            $q->execute($vars);
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), '-'],
            );
        }

        return $q->rowCount();
    }

    public function deleteEx(string $stmt, array $vars, array $constVars = [])
    {
        $count = 0;
        try {
            $q = $this->_pdo->prepare($stmt);

            foreach ($vars as $index => $var) {
                if ($index === 0) {

                    foreach ($constVars as $k => $v) {
                        $q->bindValue(':' . $k, $v);
                    }

                    $data = [];
                    $i = 0;
                    foreach ($vars[0] as $k => $v) {
                        $data[$i] = $v;
                        $q->bindParam(':' . $k, $data[$i]);
                        $i++;
                    }
                } else {
                    $i = 0;
                    foreach ($var as $v) {
                        $data[$i++] = $v;
                    }
                }

                $q->execute();

                $count = $count + $q->rowCount();
            }
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), print_r($constVars, true)],
            );
        }

        return $count;
    }

    public function insertUpdate(string $stmt, array $vars = [])
    {
        try {
            $q = $this->_pdo->prepare($stmt);
            $q->execute($vars);
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), '-'],
            );
        }
        return $q->rowCount();
    }

    public function update(string $stmt, array $vars, array $constVars = [])
    {
        $count = 0;
        try {
            $q = $this->_pdo->prepare($stmt);

            foreach ($vars as $index => $var) {
                if ($index === 0) {

                    foreach ($constVars as $k => $v) {
                        $q->bindValue(':' . $k, $v);
                    }

                    $data = [];
                    $i = 0;
                    foreach ($vars[0] as $k => $v) {
                        $data[$i] = $v;
                        $q->bindParam(':' . $k, $data[$i]);
                        $i++;
                    }
                } else {
                    $i = 0;
                    foreach ($var as $v) {
                        $data[$i++] = $v;
                    }
                }

                $q->execute();

                $count = $count + $q->rowCount();
            }
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, print_r($vars, true), print_r($constVars, true)],
            );
        }

        return $count;
    }

    public function exec(string $stmt)
    {
        try {
            $res = $this->_pdo->exec($stmt);
        } catch (\PDOException $e) {
            MWException::ThrowEx(
                errCode: MWI18nHelper::ERR_DB_SQL_STATEMENT_FAILED,
                logData: [$e->getMessage(), $stmt, '-', '-'],
            );
        }

        return $res;
    }

}
