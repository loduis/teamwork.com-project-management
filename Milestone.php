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
        return $this->rest->put("$this->_action/$id/complete");
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
        return $this->rest->put("$this->_action/$id/uncomplete");
    }

    /**
     * Get all milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getAll($params = array())
    {
        if (is_string($params)) {
            $params = array(
                'find'=> $params
            );
        }
        return $this->rest->get("$this->_action", $params);
    }

    /**
     * Get all milestone
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($project_id, $params = null)
    {
        if ($params && is_string($params)) {
            $params = array(
                'find'=> $params
            );
        }
        return $this->rest->get("projects/$project_id/$this->_action", $params);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) (isset($data['project_id']) ? $data['project_id'] : 0);
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->_action", $data);
    }
}