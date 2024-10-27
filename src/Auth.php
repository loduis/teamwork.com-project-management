<?php

namespace TeamWorkPm;

class Auth
{
    private static string $url = 'https://authenticate.teamwork.com/';

    private static array $config = [
        'url' => null,
        'key' => null,
    ];

    private static bool $is_subdomain = false;

    public static function set(string ...$args): void
    {
        $numArgs = count($args);
        if ($numArgs === 1) {
            static::$config['url'] = static::$url;
            static::$config['key'] = $args[0];
            static::$config['url'] = Factory::account()->authenticate()->url;
        } elseif ($numArgs === 2) {
            static::$config['url'] = $url = $args[0];
            static::checkSubDomain($url);
            if (static::$is_subdomain) {
                static::$config['url'] = static::$url;
            }
            static::$config['key'] = $args[1];
            if (static::$is_subdomain) {
                $url = Factory::account()->authenticate()->url;
            }
            static::$config['url'] = $url;
        }
    }

    public static function get(): array
    {
        return array_values(static::$config);
    }

    private static function checkSubDomain(string $url): void
    {
        $eu_domain = strpos($url, '.eu');

        if ($eu_domain !== false) {
            static::$url = 'https://authenticate.eu.teamwork.com/';
            $url = substr($url, 0, $eu_domain);
        }
        if (!str_contains($url, '.')) {
            static::$is_subdomain = true;
        }
    }
}
