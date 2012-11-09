<?php

abstract class TeamWorkPm_Category_Model extends TeamWorkPm_Model
{

    protected  function _init()
    {
        list ($parent, $type) = explode('-', $this->_parent);
        $this->_parent = $parent;
        $this->_action = $type . 'Categories';
        $this->_fields = array(
            'name'=>true,
            'parent'=> false
        );
    }

    /**
     * Retrieving all of a Projects Categories
     *
     * GET /projects/#{project_id}/#{resource}Categories.xml
     *
     * All the message categories for your project will be returned.
     *
     * @param int $id
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        return $this->_get("projects/$id/$this->_action");
    }

    /**
     * Creating Categories
     *
     * POST /projects/#{project_id}/#{resource}Categories.xml
     *
     * A new category will be created and attached to your specified project ID.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new TeamWorkPm_Exception('Require field project_id');
        }
        return $this->_post("projects/$project_id/$this->_action", $data);
    }
}