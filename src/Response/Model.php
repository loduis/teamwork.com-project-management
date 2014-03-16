<?php namespace TeamWorkPm\Response;

abstract class Model implements \Countable
{
    protected $string = null;

    protected $headers = [];

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
