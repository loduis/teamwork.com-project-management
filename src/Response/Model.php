<?php

namespace TeamWorkPm\Response;

abstract class Model implements \IteratorAggregate, \Countable, \ArrayAccess
{
    protected $string = null;

    protected $originalString = null;

    protected $headers = [];

    /**
     * @var object|array
     */
    protected $data = [];

    final public function __construct()
    {
    }

    abstract public function parse($data, array $headers);

    public function save(string $filename)
    {
        if (strpos($filename, '.') === false) {
            $class = get_called_class();
            $ext = strtolower(substr($class, strrpos($class, '\\') + 1));
            $filename .= '.' . $ext;
        }
        $dirname = dirname($filename);
        // create the directory if it does not exist
        if ($dirname && !is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        return file_put_contents($filename, $this->getContent());
    }

    abstract protected function getContent();

    abstract public function getOriginalContent();

    public function __toString()
    {
        return $this->getContent();
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}
