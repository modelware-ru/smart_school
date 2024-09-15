<?php
namespace MW\Shared;

class ValueChecker
{
    private $_value;
    private $_check;

    const IS_VALID = 0;
    const IS_NULL = 1;
    const IS_EMPTY = 2;
    const IS_NOT_VALID_EMAIL = 3;
    const LENGTH_IS_NOT_LESS_OR_EQUAL = 4;
    const LENGTH_IS_NOT_GREAT_OR_EQUAL = 5;
    const LENGTH_IS_NOT_EQUAL = 6;
    const VALUE_LESS = 7;
    const VALUE_GREAT = 8;
    const NOT_IN_ARRAY = 9;

    public function __construct($value)
    {
        $this->_value = $value;
        $this->_check = is_null($value) ? self::IS_NULL : self::IS_VALID;
    }

    public function notEmpty()
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'string':
                $res = strlen($this->_value) > 0;
                break;
        }

        if (!$res) {
            $this->_check = self::IS_EMPTY;
        }

        return $this;
    }

    public function validEmail()
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = filter_var($this->_value, FILTER_VALIDATE_EMAIL) !== false;

        if (!$res) {
            $this->_check = self::IS_NOT_VALID_EMAIL;
        }

        return $this;
    }

    public function lengthLessOrEqual($size)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'string':
                $res = strlen($this->_value) <= $size;
                break;
        }

        if (!$res) {
            $this->_check = self::LENGTH_IS_NOT_LESS_OR_EQUAL;
        }

        return $this;
    }

    public function lengthGreatOrEqual($size)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'string':
                $res = strlen($this->_value) >= $size;
                break;
        }

        if (!$res) {
            $this->_check = self::LENGTH_IS_NOT_GREAT_OR_EQUAL;
        }

        return $this;
    }

    public function lengthEqual($size)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'string':
                $res = strlen($this->_value) === $size;
                break;
        }

        if (!$res) {
            $this->_check = self::LENGTH_IS_NOT_EQUAL;
        }

        return $this;
    }

    public function valueLess($value)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'integer':
                $res = $this->_value < $value;
                break;
        }

        if (!$res) {
            $this->_check = self::VALUE_GREAT;
        }

        return $this;
    }

    public function valueGreat($value)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = false;
        switch (gettype($this->_value)) {
            case 'integer':
                $res = $this->_value > $value;
                break;
        }

        if (!$res) {
            $this->_check = self::VALUE_LESS;
        }

        return $this;
    }

    public function inArray($array)
    {
        if (!$this->isValid()) {
            return $this;
        }

        $res = in_array($this->_value, $array);

        if (!$res) {
            $this->_check = self::NOT_IN_ARRAY;
        }

        return $this;
    }

    public function isValid()
    {
        return $this->_check === self::IS_VALID;
    }

    public function check()
    {
        return $this->_check;
    }
}
