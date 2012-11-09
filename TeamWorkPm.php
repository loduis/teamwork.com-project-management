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
        $class = str_replace(array('/', '_'), ' ', $class);
        $class = ucwords(str_replace(' ', '_', $class));
        $class = __CLASS__ . '_' .  $class;

        return forward_static_call_array(
              array($class, 'getInstance'),
              array(self::$_COMPANY, self::$_API_KEY)
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

    /**
     * TeamWorkPm PSR-0 autoloader
     */
    public static function autoload($className)
    {
        $thisClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);
        $baseDir = __DIR__;
        $length = strlen($thisClass);

        $className = ltrim($className, '\\');

        if (substr($baseDir, - $length) === $thisClass) {
            $baseDir = substr($baseDir, 0, - $length);
        } elseif (substr($className, 0, $length + 1) === $thisClass . '_') {
            $className = substr($className, $length + 1);
        }

        $fileName  = $baseDir . DIRECTORY_SEPARATOR;
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)) {
            require $fileName;
        }
    }

    /**
     * Register TeamWorkPm's PSR-0 autoloader
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(__CLASS__ . '::autoload');
    }
}

TeamWorkPm::registerAutoloader();