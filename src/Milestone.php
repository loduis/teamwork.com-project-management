<?php

namespace TeamWorkPm;

class Milestone extends Model
{
    protected function init()
    {
        // this is the list of fields that can send the api
        $this->fields = [
            'title' => true,
            'description' => false,
            'deadline' => [
                'type' => 'integer',
                'required' => true,
            ],
            'notify' => [
                'type' => 'boolean',
                'required' => false,
            ],
            'reminder' => [
                'type' => 'boolean',
                'required' => false,
            ],
            'private' => [
                'type' => 'boolean',
                'required' => false,
            ],
            'responsible_party_ids' => true,
            'move_upcoming_milestones' => [
                'type' => 'boolean',
                'sibling' => true,
                'required' => false,
                'on_update' => true
            ],
            'move_upcoming_milestones_off_weekends' => [
                'type' => 'boolean',
                'sibling' => true,
                'required' => false,
            ],
        ];
    }

    /**
     * Complete
     *
     * PUT /milestones/#{id}/complete.xml
     *
     * Marks the specified milestone as complete. Returns Status 200 OK.
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function complete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->put("$this->action/$id/complete");
    }

    /**
     * Uncomplete
     *
     * PUT /milestones/#{id}/uncomplete.xml
     *
     * Marks the specified milestone as uncomplete. Returns Status 200 OK.
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function uncomplete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->put("$this->action/$id/uncomplete");
    }

    /**
     * Get all milestone
     *
     * @param string $filter
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getAll($filter = 'all')
    {
        return $this->fetch("$this->action", $this->getParams($filter));
    }

    /**
     * Get all milestone
     *
     * @param $project_id
     * @param string $filter
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByProject($project_id, $filter = 'all')
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        return $this->fetch(
            "projects/$project_id/$this->action",
            $this->getParams($filter)
        );
    }

    /**
     * @param $filter
     *
     * @return array
     * @throws Exception
     */
    private function getParams($filter)
    {
        $params = [];
        if ($filter) {
            $filter = (string)$filter;
            $filter = strtolower($filter);
            if ($filter !== 'all') {
                $validate = ['completed', 'incomplete', 'late', 'upcoming'];
                if (!in_array($filter, $validate)) {
                    throw new Exception('Invalid value for param filter');
                }
                $params['find'] = $filter;
            }
        }
        return $params;
    }

    /**
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function create(array $data)
    {
        $project_id = empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->post("projects/$project_id/$this->action", $data);
    }
}
