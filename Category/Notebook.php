<?php

class TeamWorkPm_Category_Notebook extends TeamWorkPm_Category_Model
{
    /**
     * @var Notebook
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return TeamWorkPm_Category_Notebook
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}