<?php namespace TeamWorkPm;

class Auth
{
    private static $_config = [
        'company' => null,
        'key'     => null
    ];

    public static function set()
    {
        $num_args = func_num_args();
        if ($num_args === 1) {
            self::$_config['company'] = 'authenticate';
            self::$_config['key']     = func_get_arg(0);
            $account       = Factory::build('account');
            $authenticate  = $account->authenticate();
            self::$_config['company'] = $authenticate->code;
        } elseif($num_args === 2) {
            self::$_config['company'] = func_get_arg(0);
            self::$_config['key']     = func_get_arg(1);
        }
    }

    public static function get()
    {
        return array_values(self::$_config);
    }
}