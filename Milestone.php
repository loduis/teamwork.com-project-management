<?php
namespace TeamWorkPm;

class Milestone extends Model
{
    protected function _init()
    {
        // this is the list of fields that can send the api
        $this->_fields = array(
            'title'       => true,
            'description' => false,
            'deadline'    => array(
                'required'=>true,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),//format YYYYMMDD
            'notify'      => array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'reminder'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'private'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'responsible_party_ids' => true,
            # USE ONLY FOR UPDATE OR PUT METHOD
            'move_upcoming_milestones'=>array(
              'sibling'=>true,
              'required'=>false,
              'attributes'=>array('type'=>'boolean')
            ),
            'move_upcoming_milestones_off_weekends'=>array(
              'sibling'=>TRUE,
              'required'=>false,
              'attributes'=>array('type'=>'boolean')
            )
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
    public function complete($id)
    {
        return $this->_put("$this->_action/$id/complete");
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
    public function unComplete($id)
    {
        return $this->_put("$this->_action/$id/uncomplete");
    }

    /**
     * Get all milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getAll($project_id = null)
    {
        return $this->_getByFilter('all', $project_id);
    }

    /**
     * Get all complete milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getCompleted($project_id = null)
    {
        return $this->_getByFilter('completed', $project_id);
    }

    /**
     * Get all incomplete milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getIncomplete($project_id = null)
    {
        return $this->_getByFilter('incomplete', $project_id);
    }

    /**
     * Get all late milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getLate($project_id = null)
    {
        return $this->_getByFilter('late', $project_id);
    }

    /**
     * Get all upcoming milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getUpcoming($project_id = null)
    {
        return $this->_getByFilter('upcoming', $project_id);
    }

    /**
     * Get all milestone by filter
     *
     * @param string $filter
     * @return TeamWorkPm\Response\Model
     */
    private function _getByFilter($filter, $project_id)
    {
        $project_id = (int) $project_id;
        $action = $project_id ? "projects/$project_id/$this->_action" : $this->_action;

        return $this->_get($action, array('find'=>$filter));
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) (empty($data['project_id']) ? 0 : $data['project_id']);
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->_post("projects/$project_id/$this->_action", $data);
    }
}