<?php

declare(strict_types=1);

namespace TeamWorkPm;

use Illuminate\Api\Http\Api as HttpApi;

final class Api
{
    /**
     * Custom options of http client
     *
     * @var array
     */
    private static $clientOptions = [];

    /**
     * The base path of api
     *
     * @var string
     */
    public const BASE_URI = 'https://{COMPANY}.teamwork.com/';

    public static function auth($company, $api)
    {
        HttpApi::auth($api, 'xxx');
        $baseUri = str_replace('{COMPANY}', $company, self::BASE_URI);
        HttpApi::baseUri($baseUri);
        $options = self::$clientOptions;
        $options['extension'] = 'json';
        HttpApi::createClient($options);
    }

    /**
     * Set the custom options for create http client
     *
     * @param  array  $options
     * @return void
     */
    public static function clientOptions(array $options)
    {
        self::$clientOptions = $options;
    }
}
