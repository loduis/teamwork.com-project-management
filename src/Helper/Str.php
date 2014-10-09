<?php namespace TeamWorkPm\Helper;

// from https://github.com/laravel/framework/blob/master/src/Illuminate/Support/Str.php

final class Str
{
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }

    /**
     * Convert all undescores into dashes
     *
     * @param string  $value
     * @return string
     */
    public static function dash($value)
    {
        return str_replace('_', '-', $value);
    }
}

