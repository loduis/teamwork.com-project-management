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

    private static $_COMPANY = NULL;

    private static $_API_KEY = NULL;

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
        if (NULL === self::$_COMPANY || NULL === self::$_API_KEY) {
            throw new TeamWorkPm_Exception('Require api company name or key');
        }
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