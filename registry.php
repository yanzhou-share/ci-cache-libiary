<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class prefix_Registry {

    private $data = array();

    public function __construct() {}

    public function  __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    final public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    final public function get($name)
    {
        if(array_key_exists($name, $this->data))
        {
            return $this->data[$name];
        }
        else
        {
            return FALSE;
        }
    }
}
