<?php
namespace TeamWorkPm\Response;

abstract class Model implements \Countable
{
    protected $string = null;

    protected $headers = array();

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

        return file_put_contents($filename, $this->getContent());
    }

    abstract protected function getContent();

    public function __toString()
    {
        return $this->getContent();
    }

    public function toArray()
    {
        $array = new \ArrayObject($this);
        return $array->getArrayCopy();
    }

    protected static function camelize($string)
    {

        $replace = preg_replace_callback('/_(.)/',
                function($matches) {
                    return strtoupper($matches[1]);
                }, $string);
        $replace = preg_replace_callback('/-(.)/',
                function($matches) {
                    return strtoupper($matches[1]);
                }, $replace);
                
        return $replace;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function count()
    {
        $count = 0;
        foreach($this as $key=>$value) {
            if (is_numeric($key) && !is_scalar($value)) {
                ++ $count;
            }
        }
        return $count;
    }
}
