<?php

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;

class People extends Resource
{
    protected function init()
    {
        $this->fields = [
            'view_messages_and_files' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_tasks_and_milestones' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_time' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_notebooks' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_risk_register' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_invoices' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'view_links' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_tasks' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_milestones' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_taskLists' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_messages' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_files' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_time' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_links' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'set_privacy' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'can_be_assigned_to_tasks_and_milestones' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'project_administrator' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
            'add_people_to_project' => [
                'type' => 'integer',
                'validate' => [1, 0],
            ],
        ];
        $this->action = $this->parent = 'permissions';
    }

    /**
     * @param $project_id
     * @param $person_id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get($project_id, $person_id)
    {
        $this->validates($project_id, $person_id);
        return $this->rest->get("/projects/$project_id/people/$person_id");
    }

    /**
     * @param int $project_id
     * @param int $person_id
     *
     * @return bool
     * @throws Exception
     */
    public function add(int $project_id, int $person_id): bool
    {
        $this->validates($project_id, $person_id);

        return $this->rest->post("projects/$project_id/people/$person_id");
    }

    /**
     * Update a users permissions on a project
     * PUT /projects/{id}/people/{id}.xml
     * Sets the permissions of a given user on a given project.
     *
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(object|array $data)
    {
        $data = arr_obj($data);
        $project_id = (int) ($data['project_id'] ?? 0);
        $person_id = (int) ($data['person_id'] ?? 0);
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        if ($person_id <= 0) {
            throw new Exception('Required field person_id');
        }

        return $this->rest->put("projects/$project_id/people/$person_id", $data) === true;
    }

    /**
     * @param $project_id
     * @param $person_id
     *
     * @return mixed
     * @throws Exception
     */
    public function delete(int $project_id, int $person_id)
    {
        $this->validates($project_id, $person_id);

        return $this->rest->delete("/projects/$project_id/people/$person_id");
    }

    /**
     * @param $project_id
     * @param $person_id
     *
     * @throws Exception
     */
    private function validates($project_id, $person_id): void
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $person_id = (int)$person_id;
        if ($person_id <= 0) {
            throw new Exception('Invalid param person_id');
        }
    }
}
