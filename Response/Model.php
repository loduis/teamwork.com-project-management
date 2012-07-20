<?php

abstract class TeamWorkPm_Response_Model
{
    protected $_string;

    protected $_array = array();

    final public function __construct()
    {


    }

    abstract public function parse($data, $headers);

    public function save($filename)
    {
        file_put_contents($filename, $this->_getContent());
    }

    abstract protected function _getContent();

    public function __toString()
    {
        return $this->_string;
    }

    public function toArray()
    {
        return $this->_array;
    }
}