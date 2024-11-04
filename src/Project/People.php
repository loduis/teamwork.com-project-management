<?php

declare(strict_types = 1);

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/permissions/put-projects-id-people-json
 */
class People extends Resource
{
    protected ?string $parent = 'permissions';

    protected ?string $action = 'permissions';

    protected function init()
    {
        $this->fields = [
            'can_view_project_update' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_tasks_and_milestones' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_milestones' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_task_lists' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_estimated_time' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_tasks' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_messages_and_files' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_messages' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_files' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_time_log' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_all_time_logs' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_log_time' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_view_project_budget' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_notebook' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_notebooks' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_risk_register' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'view_links' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_links' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_view_forms' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_add_forms' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'can_manage_custom_fields' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
            'project_administrator' => [
                'type'   => 'boolean',
                'transform' => 'camel'
            ],
        ];
    }

    /**
     * @param int $projectId
     * @param int $personId
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $projectId, int $personId)
    {
        $this->validates(compact('projectId', 'personId'));

        return $this->fetch("/projects/$projectId/people/$personId");
    }

    /**
     * @param int $projectId
     * @param int $personId
     *
     * @return bool
     * @throws Exception
     */
    public function add(int $projectId, int $personId): bool
    {
        $this->validates(compact('projectId', 'personId'));

        /** @var bool */
        return $this->post("projects/$projectId/people/$personId");
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
        $project_id = (int) $data->pull('project_id');
        $person_id = (int) $data->pull('person_id');
        $this->validates(compact('project_id', 'person_id'));

        /** @var bool */
        return $this->put(
            "projects/$project_id/people/$person_id",
            $data
        );
    }

    /**
     * @param int $projectId
     * @param int $personId
     *
     * @return mixed
     * @throws Exception
     */
    public function delete(int $projectId, int $personId)
    {
        $this->validates(compact('projectId', 'personId'));

        return $this->del("/projects/$projectId/people/$personId");
    }
}
