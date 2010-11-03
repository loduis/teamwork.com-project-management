<?php

class TeamWorkPm_Project extends TeamWorkPm_Model
{
    /**
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * @var Project
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Project
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}