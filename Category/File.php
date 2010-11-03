<?php

class TeamWorkPm_Category_File extends TeamWorkPm_Category_Model
{

    /**
     * @var File
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return File
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}