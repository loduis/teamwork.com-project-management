<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;

class Time extends Model
{
    protected function init()
    {
        $this->fields = [
            'description' => true,
            'person_id' => true,
            'date' => [
                'required' => true,
                'type' => 'integer',
                'length' => 6,
            ],
            'hours' => [
                'required' => true,
                'type' => 'integer',
                'length' => 3,
            ],
            'minutes' => [
                'required' => false,
                'type' => 'integer',
                'length' => 2,
            ],
            'time' => true,
            'isbillable' => false,
            'tags' => [
                'required' => false,
                'type' => 'string',
            ],
        ];
        $this->parent = 'time-entry';
    }

    /**
     * Inserta un time entry ya sea para
     * un projecto o para un todo item
     *
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function create(array $data)
    {
        $id = 0;
        if (!empty($data['task_id'])) {
            $id = (int)$data['task_id'];
            $resource = 'todo_items';
        } elseif (!empty($data['project_id'])) {
            $id = (int)$data['project_id'];
            $resource = 'projects';
        }
        if ($id <= 0) {
            throw new Exception('Required field project_id or task_id');
        }
        return $this->post("$resource/$id/$this->action", $data);
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
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getAll(array $params = [])
    {
        return $this->fetch("$this->action", $params);
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
     * @param $project_id
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByProject($project_id, array $params = [])
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        return $this->fetch("projects/$project_id/$this->action", $params);
    }

    /**
     * Retrieve all To-do Item Times
     *
     * GET /todo_items/#{todo_item_id}/time_entries
     *
     * Retrieves all of the time entries from a submitted todo item.
     *
     * @param $task_id
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByTask($task_id, array $params = [])
    {
        $task_id = (int)$task_id;
        if ($task_id <= 0) {
            throw new Exception('Invalid param task_id');
        }
        return $this->fetch("todo_items/$task_id/$this->action", $params);
    }
}
