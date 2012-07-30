<?php

class TeamWorkPm_Task_List extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'=>TRUE,
            'private'=>array('required'=>FALSE, 'attributes'=>array('type'=>'boolean')),
            'tracked'=>array('required'=>FALSE, 'attributes'=>array('type'=>'boolean')),
            'description'=>FALSE,
            'milestone_id'=>FALSE,
            'todo_list_template_id'=>FALSE
        );
        $this->_parent = 'todo-list';
        $this->_action = 'todo_lists';
    }
    /**
     * Retrieve all lists in a Project

     * GET /projects/#{project_id}/todo_lists.xml?filter=#{filter}
     *
     * Retrieves all todo lists in a project. You can further filter these results with the
     * 'filter' query. You can set this to 'all', 'pending', 'late' and 'finished'. 'pending'
     * lists incomplete tasks. The filter is defaulted to 'pending'
     *
     * @param <type> $id
     * @param <type> $filter
     * @return object
     */
    public function getAllByProject($project_id, $params = array())
    {

        return $this->_getByStatus($project_id, 'all', $params);
    }

    public function getActiveByProject($project_id, $params = array())
    {

        return $this->_getByStatus($project_id, 'active', $params);
    }

    public function getCompletedByProject($project_id, $params = array())
    {

        return $this->_getByStatus($project_id, 'completed', $params);
    }


    private function _getByStatus($id, $status, $params)
    {
        $params['status'] = $status;
        return $this->_get("projects/$id/$this->_action", $params);
    }

    /**
     * Reorder lists
     *
     * POST /projects/#{project_id}/todo_lists/reorders
     * Reorders the lists in the project according to the ordering given.
     * Any lists that are not explicitly specified will be positioned after the lists that are specified.
     *
     * @param int $project_id
     * @param array $ids
     * @return bool
     */
    public function reorder($project_id, array $ids)
    {
        $project_id = (int) $project_id;
        return $this->_post("projects/$project_id/$this->_action/reorder", $ids);
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