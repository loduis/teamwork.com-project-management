<?php

class TeamWorkPm_Comment_Milestone extends TeamWorkPm_Comment_Model
{
    /**
     * @var Milestone
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Milestone
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }

    protected function  _init()
    {
        parent::_init();
        $this->_resource = 'milestones';
    }
}