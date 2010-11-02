<?php

/**
 *
 * @package    TeamWorkPm
 * Copyright   @ Nomad Web Ventures Inc 2005-2010
 * @license    licence.txt
 * @author     Loduis Madariaga Barrios
 * @version    0.0.1-dev
 */

if (!function_exists('forward_static_call_array')) {
    function forward_static_call_array($function , array $parameters = array()) {
        if (is_array($function)) {
            list ($class, $method) = $function;
            $function = $class . '::' . $method;
        }
        $c = false;
        $params = '';
        foreach ($parameters as $param) {
            if ($c) {
                $params .= ',';
            }
            $params .= var_export($param, true);
            $c = true;
        }
        $eval = 'return ' . $function . '(' . $params . ');';
        
        return eval($eval);
    }
}

class TeamWorkPm
{
    const TODO_LIST = 'Todo_List';

    const TODO_ITEM = 'Todo_Item';

    const POST       = 'Post';

    const MILESTONE  = 'Milestone';


    const COMPANY = 'phpapi';

    const API_KEY = 'mess146balas';

    
    private static
        $_instances;
    
    private function  __construct()
    {

    }
    /**
     *
     * @param string $class
     * @return TeamWorkPm_Abstract
     */
    final public static function factory($class)
    {
        $class = __CLASS__ . '_' .  $class;
        
        if (null === self::$_instances[$class]) {
            self::$_instances[$class] = forward_static_call_array(
              array($class, 'getInstance'),
              array(self::COMPANY, self::API_KEY)
            );
        }
        return self::$_instances[$class];
    }

    final private function __clone() {}
}