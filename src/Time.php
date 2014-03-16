<?php namespace TeamWorkPm;

class Time extends Model
{
    protected function init()
    {
        $this->fields = [
            'description' => true,
            'person_id'   => true,
            'date'=>[
                'required' => true,
                'type'     => 'integer',
                'length'   => 6
            ],
            'hours' => [
                'required' => true,
                'type'     => 'integer',
                'length'   => 3
            ],
            'minutes'=>[
                'required' => false,
                'type'     => 'integer',
                'length'   => 2
            ],
            'time'=>true,
            'isbillable'=>false
        ];
        $this->parent = 'time-entry';
        //$this->action = 'time_entries';
    }

    /**
     * Inserta un time entry ya sea para
     * un projecto o para un todo item
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $id = 0;
        if (!empty($data['task_id'])) {
            $id = (int) $data['task_id'];
            $resource = 'todo_items';
        } elseif (!empty($data['project_id'])) {
            $id = (int) $data['project_id'];
            $resource = 'projects';
        }
        if ($id <= 0) {
            throw new Exception('Required field project_id or task_id');
        }
        return $this->rest->post("$resource/$id/$this->action", $data);
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
     * @return TeamWorkPm\Response\Model
     */

    public function getAll(array $params = [])
    {
        return $this->rest->get("$this->action", $params);
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
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($project_id, array $params = [])
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        return $this->rest->get("projects/$project_id/$this->action", $params);
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
     * @return TeamWorkPm\Response\Model
     */
    public function getByTask($task_id, array $params = [])
    {
        $task_id = (int) $task_id;
        if ($task_id <= 0) {
            throw new Exception('Invalid param task_id');
        }
        return $this->rest->get("todo_items/$task_id/$this->action", $params);
    }
}
