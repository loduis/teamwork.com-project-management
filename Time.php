<?php

class TeamWorkPm_Time extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'description'=>TRUE,
            'person_id'=>FALSE,
            'date'=>TRUE,
            'hours'=>TRUE,
            'minutes'=>array('required'=>FALSE, 'default'=>0),
            'time'=>TRUE,
            'isbillable'=>FALSE
        );
        $this->_parent = 'time-entry';
        $this->_action = 'time_entries';
    }

    /**
     * Inserta un time entry ya sea para
     * un projecto o para un todo item
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $id      = NULL;
        if (!empty($data['task_id'])) {
            $id = (int) $data['task_id'];
            $resource = 'todo_items';
        } elseif (!empty($data['project_id'])) {
            $id = (int) $data['project_id'];
            $resource = 'projects';
        }
        if (!$id) {
            throw new TeamWorkPm_Exception('Require field project_id or task_id');
        }
        return $this->_post("$resource/$id/$this->_action", $data);
    }

    /**
     * Optional Parameters
     *
     * PAGE : numeric - The page to start retrieving entries from
     * ( e.g: page=1 gives records 1 - 50, page=2 gives records 51-99 etc)
     * FROMDATE : string (YYYYMMDD) - The start date to retrieve from
     * FROMTIME : string (HH:MM) - The start time only if FROMDATE is passed
     * TODATE : string (YYYYMMDD) - The end date to retrieve to
     * TOTIME : string (HH:MM) - The end time only if TODATE is passed
     *
     * @param string $id
     * @param array $params
     * @return TeamWorkPm_Response_Model
     */

    public function getAll(array $params = array())
    {
        return $this->_get("$this->_action", $params);
    }

    /**
     * Optional Parameters
     *
     * PAGE : numeric - The page to start retrieving entries from
     * ( e.g: page=1 gives records 1 - 50, page=2 gives records 51-99 etc)
     * FROMDATE : string (YYYYMMDD) - The start date to retrieve from
     * FROMTIME : string (HH:MM) - The start time only if FROMDATE is passed
     * TODATE : string (YYYYMMDD) - The end date to retrieve to
     * TOTIME : string (HH:MM) - The end time only if TODATE is passed
     *
     * @param int $id
     * @param array $params
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($id, array $params = array())
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new TeamWorkPm_Exception('Require parameter id.');
        }
        return $this->_get("projects/$id/$this->_action", $params);
    }

    /**
     * Retrieve all To-do Item Times
     *
     * GET /todo_items/#{todo_item_id}/time_entries
     *
     * Retrieves all of the time entries from a submitted todo item.
     *
     * @param int $id
     * @param array $params
     * @return TeamWorkPm_Response_Model
     */
    public function getByTask($id, array $params = array())
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new TeamWorkPm_Exception('Require parameter id.');
        }
        return $this->_get("todo_items/$id/$this->_action", $params);
    }
}
