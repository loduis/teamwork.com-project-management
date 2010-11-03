<?php

class TeamWorkPm_Time extends TeamWorkPm_Model
{
    /**
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * @var Time
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Time
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}