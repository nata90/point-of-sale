<?php

namespace tests\unit\support;

use ArrayIterator;
use yii\web\Session;

/**
 * In-memory session implementation for CLI PHPUnit tests.
 */
class ArraySession extends Session
{
    /** @var array */
    private $_data = [];

    /** @var bool */
    private $_active = false;

    /** @var string */
    private $_id = 'test-session-id';

    public function getIsActive()
    {
        return $this->_active;
    }

    public function open()
    {
        $this->_active = true;
    }

    public function close()
    {
        $this->_active = false;
    }

    public function destroy()
    {
        $this->_data = [];
        $this->_active = false;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($value)
    {
        $this->_id = (string) $value;
    }

    public function regenerateID($deleteOldSession = false)
    {
        $this->_id = 'test-session-' . uniqid('', true);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_data);
    }

    public function getCount()
    {
        return count($this->_data);
    }

    public function get($key, $defaultValue = null)
    {
        return array_key_exists($key, $this->_data) ? $this->_data[$key] : $defaultValue;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function remove($key)
    {
        if (array_key_exists($key, $this->_data)) {
            $value = $this->_data[$key];
            unset($this->_data[$key]);
            return $value;
        }
        return null;
    }

    public function removeAll()
    {
        $this->_data = [];
    }

    public function has($key)
    {
        return array_key_exists($key, $this->_data);
    }
}
