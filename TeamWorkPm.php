<?php

/**
 *
 * @package    TeamWorkPm
 * Copyright   @ Loduis Madariaga
 * @license    LICENCE.txt
 * @version    0.0.1-dev
 */

final class TeamWorkPm
{

    private static $_COMPANY = 'phpapi';

    private static $_API_KEY = 'mess146balas';

    private function  __construct()
    {

    }
    /**
     *
     * @param string $class
     * @return TeamWorkPm_Model
     */
    public static function factory($class)
    {
        $class = str_replace(array('/', '_'), ' ', $class);
        $class = ucwords(str_replace(' ', '_', $class));
        $class = __CLASS__ . '_' .  $class;

        return forward_static_call_array(
              array($class, 'getInstance'),
              array(self::$_COMPANY, self::$_API_KEY, $class)
        );
    }

    public static function setAuth($company, $key)
    {
        self::$_COMPANY = $company;
        self::$_API_KEY = $key;
    }

    public static function setFormat($value)
    {
        TeamWorkPm_Rest::setFormat($value);
    }

    private function __clone()
    {

    }
}