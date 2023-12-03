<?php

declare(strict_types=1);

namespace TeamWorkPm;

use Illuminate\Api\Http\Resource as ApiResource;
use Illuminate\Api\Http\Restable;
use Illuminate\Support\Str;

/**
 * Base resource
 */
abstract class Resource extends ApiResource
{
    /**
     * The primary key for the model.
     *
     * @var array
     */
    protected $primaryKey = [
        'id' => 'string',
    ];

    use Restable;
    use Support\Getting;


    /**
     * Dynamically retrieve attributes on the container.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (!$this->hasAttribute($key)) {
            $key2 = Str::snake($key, '-');
            if ($this->hasAttribute($key2)) {
                $key = $key2;
            } else {
                $key2 = Str::snake($key);
                if ($this->hasAttribute($key2)) {
                    $key = $key2;
                } else {
                    $key2 = static::camelLastUpper($key2);
                    if ($this->hasAttribute($key2)) {
                        $key = $key2;
                    }
                }
            }
        }

        return $this->getAttribute($key);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string|mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            $key2 = str_replace('_', '-', $offset);
            if ($this->offsetExists($key2)) {
                $offset = $key2;
            } else {
                $key2 = Str::camel($offset);
                if ($this->offsetExists($key2)) {
                    $offset = $key2;
                } else {
                    $key2 = str_replace('-', '_', $offset);
                    $key2 = static::camelLastUpper($key2);
                    if ($this->offsetExists($key2)) {
                        $offset = $key2;
                    }
                }
            }
        }

        return $this->$offset;
    }

    private static function camelLastUpper(string $value): string
    {
        $parts = explode('_', $value);
        $index = array_key_last($parts);
        $parts[$index] = strtoupper($parts[$index]);

        return implode($parts);
    }
}
