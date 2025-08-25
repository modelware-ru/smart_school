<?php
namespace MW\Shared;

class OperationResult
{
    const UC_SUCCESS = 'ok';
    const UC_FAIL = 'fail';
    const ERROR = 'error';

    private string $_type;
    private array $_data;
    private array $_test;

    public function __construct($type, $data = [], $test = [])
    {
        $this->_type = $type;
        $this->_data = $data;
        $this->_test = $test;
    }
    
    public function isOk()
    {
        return $this->_type === self::UC_SUCCESS;
    }

    public function isFailed()
    {
        return $this->_type === self::UC_FAIL;
    }

    public function hasError()
    {
        return $this->_type === self::ERROR;
    }

    public function setData(mixed $data)
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function json()
    {
        return \json_encode($this->array(), JSON_UNESCAPED_UNICODE);
    }

    public function array()
    {
        $res = [
            'status' => $this->_type,
            'data' => $this->_data,
        ];

        if (defined('PHPUNIT')) {
            $res['test'] = $this->_test;
        }

        return $res;
    }
}
