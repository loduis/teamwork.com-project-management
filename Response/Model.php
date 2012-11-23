<?php
namespace TeamWorkPm\Response;

abstract class Model
{
    protected $_string = null;

    protected $_headers = array();

    final public function __construct()
    {
    }

    abstract public function parse($data, array $headers);

    public function save($filename)
    {
        if (strpos($filename, '.') === false) {
            $class = get_called_class();
            $ext   = strtolower(substr($class, strrpos($class, '\\') + 1));
            $filename .= '.' . $ext;
        }
        $dirname = dirname($filename);
        // creamos el directorio en caso de que no exista
        if ($dirname && !is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        return file_put_contents($filename, $this->_getContent());
    }

    abstract protected function _getContent();

    public function __toString()
    {
        return $this->_getContent();
    }

    public function toArray()
    {
        return self::_toArray($this);
    }

    protected static function _camelize($lowerCaseAndUnderscoredWord)
    {

        $replace = preg_replace('/_(.)/e','strtoupper(\'$1\');', $lowerCaseAndUnderscoredWord);
        $replace = preg_replace('/-(.)/e','strtoupper(\'$1\');', $replace);
        return $replace;
    }

    private static function _toArray($source)
    {
        $destination = array();
        foreach ($source as $key=>$value) {
            $destination[$key] = is_scalar($value) ? $value : self::_toArray($value);
        }
        return $destination;
    }

    public function getHeaders()
    {
        return $this->_headers;
    }
}