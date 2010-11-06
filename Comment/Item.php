<?php

class TeamWorkPm_Comment_Item extends TeamWorkPm_Comment_Model
{
    protected function  _init()
    {
        parent::_init();
        $this->_resource = 'todo_items';
    }
}