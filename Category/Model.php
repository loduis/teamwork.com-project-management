<?php

abstract class TeamWorkPm_Category_Model extends TeamWorkPm_Model
{

    public function getByProjectId($id)
    {
        return $this->_get("projects/$id/$this->_action");
    }

    protected  function _init()
    {
        list ($parent, $type) = explode('-', $this->_parent);
        $this->_parent = $parent;
        $this->_action = $type . 'Categories';
        $this->_fields = array('name');
    }
}