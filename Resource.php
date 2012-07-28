<?php

class TeamWorkPm_Resource extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'name' => TRUE,
            'description' => FALSE,
            'private'=>FALSE,
            'code' => FALSE,
            'width' => FALSE,
            'height' => FALSE,
            'category_id' => FALSE,
            'category_name'=>FALSE,
            'notify'=>FALSE
        );
    }

    /**
     * List All Resources
     *
     * GET /resources
     *
     * Lists all resources on projects that the authenticated user is associated with.
     *
     * @return TeamWorkPm_Response_Model
     */
    public function getAll()
    {
        return $this->_get("$this->_action");
    }

    /**
     * List Resources on a Project
     *
     * GET /projects/#{project_id}/resources.xml
     *
     * This lets you query the list of resources for a project.
     *
     * @param type $id
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        return $this->_get("projects/$id/$this->_action");
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new TeamWorkPm_Exception('Require field project id');
        }
        return $this->_post("projects/$project_id/$this->_action", $data);
    }
}