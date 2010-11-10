<?php

abstract class TeamWorkPm_Category_Model extends TeamWorkPm_Model
{
    protected  function _init()
    {
        list ($parent, $type) = explode('-', $this->_parent);
        $this->_parent = $parent;
        $this->_action = $type . 'Categories';
        $this->_fields = array('name'=>true);
    }
    /**
     * Retrieving all of a Projects Categories
     *
     * GET /projects/#{project_id}/messageCategories.xml
     *
     * All the message categories for your project will be returned.
     *
     * @param int $id
     * @return array|SimpleXMLElement
     */
    public function getByProjectId($id)
    {
        return $this->_get("projects/$id/$this->_action");
    }
}