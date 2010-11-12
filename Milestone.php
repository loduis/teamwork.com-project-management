<?php

class TeamWorkPm_Milestone extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'title'=>true,
            'deadline'=>true,//format YYYYMMDD
            'notify'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'reminder'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'isprivate'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'responsible_party_id'=>true
        );
    }

    /**
     * Complete
     *
     * PUT /milestones/#{id}/complete.xml
     *
     * Marks the specified milestone as complete. Returns Status 200 OK.
     * @param int $id
     * @return bool
     */
    public function markAsComplete($id)
    {
        return $this->_put("{$this->_action}/$id/complete");
    }

    /**
     * Uncomplete
     *
     * PUT /milestones/#{id}/uncomplete.xml
     *
     * Marks the specified milestone as uncomplete. Returns Status 200 OK.
     *
     * @param int $id
     * @return bool
     */
    public function markAsUnComplete($id)
    {
        return $this->_put("{$this->_action}$id/uncomplete");
    }

    /**
     * List All Milestones
     *
     * GET /milestones.xml?find=[all|completed|incomplete|late|upcoming]
     *
     * Lists all milestones on projects that the authenticated user is associated with.
     * You can set the "find" option to return only those milestones that are incomplete, completed, upcoming or late.
     * By default all milestones will be returned.
     *
     * @param string $filter
     * @return array|SimpleXMLElement
     */
    public function getAll($filter = 'all')
    {
        if (!in_array($filter, array('all', 'completed', 'incomplete', 'late', 'upcoming'))) {
            throw new TeamWorkPm_Exception('Invalid filter');
        }
        return $this->_get($this->_action, array('find'=>$filter));
    }
}