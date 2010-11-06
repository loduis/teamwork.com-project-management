<?php

class TeamWorkPm_Comment_Milestone extends TeamWorkPm_Comment_Model
{
    protected function  _init()
    {
        parent::_init();
        $this->_resource = 'milestones';
    }
}