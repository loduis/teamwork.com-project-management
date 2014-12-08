<?php namespace TeamWorkPm;

class Auth
{
    const URL = 'https://authenticate.teamworkpm.net/';

    private static $config = [
        'url' => null,
        'key' => null
    ];

    public static function set()
    {
        $num_args = func_num_args();
        if ($num_args === 1) {
            self::$config['url'] = self::URL;
            self::$config['key'] = func_get_arg(0);
            self::$config['url'] = Factory::build('account')->authenticate()->url;
        } elseif ($num_args === 2) {
            self::$config['url'] = $url = func_get_arg(0);
            if ($is_subdomain = (strpos($url, '.') === false)) {
                self::$config['url'] = self::URL;
            }
            self::$config['key']  = func_get_arg(1);
            if ($is_subdomain) {
                $url = Factory::build('account')->authenticate()->url;
            }
            self::$config['url'] = $url;
        }
    }

    public static function get()
    {
        return array_values(self::$config);
    }
}
