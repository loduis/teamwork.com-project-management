<?php

class TeamWorkPm_Comment extends TeamWorkPm_Model
{
    /**
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * @var Comment
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Comment
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}