<?php

namespace TeamWorkPm\Response;

/**
 * @template TKey of array-key
 * @template TValue
 * @implements \ArrayAccess<TKey,TValue>
 * @implements \IteratorAggregate<TKey,TValue>
*/
abstract class Model implements \IteratorAggregate, \Countable, \ArrayAccess
{
    protected ?string $string = null;

    protected ?string $originalString = null;

    protected array $headers = [];

    /**
     * @var array|\ArrayObject
     */
    protected array|\ArrayObject $data = [];

    final public function __construct()
    {
    }

    /**
     *
     * @param string $data
     * @param array $headers
     * @return static|int|bool|null
     */
    abstract public function parse(string $data, array $headers): static|int|bool|null;

    public function save(string $filename): bool
    {
        if (strpos($filename, '.') === false) {
            $class = static::class;
            $ext = strtolower(substr($class, (int) strrpos($class, '\\') + 1));
            $filename .= '.' . $ext;
        }
        $dirname = dirname($filename);
        // create the directory if it does not exist
        if ($dirname && !is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        return file_put_contents($filename, $this->getContent()) !== false;
    }

    /**
     *
     * @return string
     */
    abstract protected function getContent(): string ;

    /**
     *
     * @return string
     */
    abstract public function getOriginalContent(): string;

    public function __toString(): string
    {
        return $this->getContent();
    }

    public function toArray(): array
    {
        return (array) $this->data;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     *
     * @return \Traversable<TKey,TValue>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator((array) $this->data);
    }

    public function count(): int
    {
        return count((array) $this->data);
    }

    /**
     *
     * @param TKey|null $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            /** @psalm-suppress NullArgument */
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     *
     * @param TKey $offset
     * @return boolean
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     *
     * @param TKey $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     *
     * @param TKey $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    /**
     *
     * @param TKey $name
     * @return mixed
     */
    public function __get(mixed $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Undocumented function
     *
     * @param TKey $name
     * @param mixed $value
     */
    public function __set(mixed $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     *
     * @param TKey $name
     * @return boolean
     */
    public function __isset(mixed $name)
    {
        return isset($this->data[$name]);
    }

    /**
     *
     * @param TKey $name
     */
    public function __unset(mixed $name)
    {
        unset($this->data[$name]);
    }
}
