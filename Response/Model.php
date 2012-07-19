<?php

abstract class TeamWorkPm_Response_Model  implements IteratorAggregate
{
    protected $_object;

    protected $_array;

    protected $_string;

    private $_position = 0;


    public function __construct()
    {
        $this->_position = 0;
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

    public function __get($name)
    {
        return $this->_object->$name;
    }

    //ITERATOR METHOD

    protected function _setArray($object)
    {
        $this->_array = get_object_vars($object);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_array);
    }

}