<?php

class TeamWorkPm_Comment_Item extends TeamWorkPm_Comment_Model
{
    /**
     * @var Item
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Item
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
        $this->_resource = 'todo_items';
    }
}