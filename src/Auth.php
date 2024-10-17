<?php

namespace TeamWorkPm;

class Auth
{
    private static $url = 'https://authenticate.teamwork.com/';

    private static $config = [
        'url' => null,
        'key' => null,
    ];

    private static $is_subdomain = false;

    public static function set()
    {
        $num_args = func_num_args();
        if ($num_args === 1) {
            static::$config['url'] = static::$url;
            static::$config['key'] = func_get_arg(0);
            static::$config['url'] = Factory::build('account')->authenticate()->url;
        } elseif ($num_args === 2) {
            static::$config['url'] = $url = func_get_arg(0);
            static::checkSubDomain($url);
            if (static::$is_subdomain) {
                static::$config['url'] = static::$url;
            }
            static::$config['key'] = func_get_arg(1);
            if (static::$is_subdomain) {
                $test = Factory::build('account')->authenticate();
                $url = $test->url;
            }
            static::$config['url'] = $url;
        }
    }

    public static function get()
    {
        return array_values(static::$config);
    }

    private static function checkSubDomain($url)
    {
        $eu_domain = strpos($url, '.eu');

        if ($eu_domain !== false) {
            static::$url = 'https://authenticate.eu.teamwork.com/';
            $url = substr($url, 0, $eu_domain);
        }
        if (strpos($url, '.') === false) {
            static::$is_subdomain = true;
        }
    }
}
