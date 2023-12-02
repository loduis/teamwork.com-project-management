<?php

declare(strict_types=1);

namespace TeamWorkPm;

final class Account extends \Illuminate\Api\Http\Resource
{
    use Support\Getting;

    /**
     * The resource path
     *
     * @var string
     */
    protected static $path = 'account';

    /**
     * Get the company resource
     *
     * @return static
     */
    public static function get()
    {
        return self::instanceGetRequest();
    }
}
