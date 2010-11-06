<?php

class TeamWorkPm_Account extends TeamWorkPm_Model
{
    public function getLastActivity($project_id = null)
    {
        $action = 'activity';
        if (null !== $project_id) {
            $action = "projects/$project_id/$action";
        }
        return $this->_get($action);
    }
    
    public function get($id)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }
    
    public function  insert(array $data)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }

    public function  update(array $data)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }

    public function  delete($id)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }
}