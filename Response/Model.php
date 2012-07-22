<?php

abstract class TeamWorkPm_Response_Model implements IteratorAggregate
{
    protected $_string;
    protected $_object;

    final public function __construct()
    {
        $this->_object = new stdClass();
    }

    abstract public function parse($data);

    public function save($filename)
    {
        if (strpos($filename, '.') === FALSE) {
            $class = get_called_class();
            $ext   = strtolower(substr($class, strrpos($class, '_') + 1));
            $filename .= '.' . $ext;
        }
        $dirname = dirname($filename);
        // creamos el directorio en caso de que no exista
        if ($dirname && !is_dir($dirname)) {
            mkdir($dirname, 0777, TRUE);
        }
        file_put_contents($filename, $this->_getContent());
    }

    abstract protected function _getContent();

    public function __get($name)
    {
        return $this->_object->$name;
    }

    public function __toString()
    {
        return $this->_getContent();
    }

    public function toArray()
    {
        return self::_toArray($this->_object);
    }

    protected static function _camelize($lowerCaseAndUnderscoredWord)
    {

        $replace = preg_replace('/_(.)/e','strtoupper(\'$1\');', $lowerCaseAndUnderscoredWord);
        $replace = preg_replace('/-(.)/e','strtoupper(\'$1\');', $replace);
        return $replace;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_object);
    }

    private static function _toArray($source)
    {
        $destination = array();
        foreach ($source as $key=>$value) {
            $destination[$key] = is_scalar($value) ? $value : self::_toArray($value);
        }
        return $destination;
    }
}