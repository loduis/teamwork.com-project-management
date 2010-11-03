<?php

class TeamWorkPm_Category_Message extends TeamWorkPm_Category_Model
{
    /**
     * @var Message
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Message
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }
}