<?php

class TeamWorkPm_Time_Tracking extends TeamWorkPm_Model
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
        $id = NULL;
        if (!empty($data['todo_item_id'])) {
            $id = (int) $data['todo_item_id'];
            $is_item = true;
        } elseif (!empty($data['project_id'])) {
            $id = (int) $data['project_id'];
        }
        if ($id) {
            throw new TeamWorkPm_Exception('Require field project id or todo item id');
        }
        $action = "projects/$id/$this->_action";
        if ($is_item) {
            $action = "todo_items/$id/$this->_action";
        }
        return $this->_post($action, $data);
    }

    /**
     * Optional Parameters

     * PAGE : numeric - The page to start retrieving entries from
     * ( e.g: page=1 gives records 1 - 50, page=2 gives records 51-99 etc)
     * FROMDATE : string (YYYYMMDD) - The start date to retrieve from
     * FROMTIME : string (HH:MM) - The start time only if FROMDATE is passed
     * TODATE : string (YYYYMMDD) - The end date to retrieve to
     * TOTIME : string (HH:MM) - The end time only if TODATE is passed
     *
     * @param string $id
     * @param string $params
     * @return object
     */

    public function getAll($params = array())
    {
        return $this->_get("$this->_action", $params);
    }

    /**
     * Optional Parameters

     * PAGE : numeric - The page to start retrieving entries from
     * ( e.g: page=1 gives records 1 - 50, page=2 gives records 51-99 etc)
     * FROMDATE : string (YYYYMMDD) - The start date to retrieve from
     * FROMTIME : string (HH:MM) - The start time only if FROMDATE is passed
     * TODATE : string (YYYYMMDD) - The end date to retrieve to
     * TOTIME : string (HH:MM) - The end time only if TODATE is passed
     *
     * @param string $id
     * @param string $params
     * @return array | SimpleXMLElement
     */
    public function getByProjectId($id, $params = array())
    {
        return $this->_get("projects/$id/$this->_action", $params);
    }

    /**
     *
     * @param string $id
     * @param string $params
     * @return array | SimpleXMLElement
     */
    public function getByTodoItemId($id, $params = array())
    {
        return $this->_get("todo_items/$id/$this->_action", $params);
    }
}