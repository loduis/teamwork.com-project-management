<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/task-lists/post-projects-id-tasklists-json
 */
class Task_List extends Model
{
    protected string|array $fields = 'tasklists';

    protected ?string $action = 'tasklists';

    protected ?string $parent = 'todo-list';

    /**
     * Get all Task Lists
     *
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function all(array|object $params = []): Response
    {
        return $this->fetch("$this->action", $params);
    }

    /**
     * Get all Task Lists for a Project
     *
     * @param int $id
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, object| array $params = []): Response
    {
        return $this->fetch("projects/$id/$this->action", $params);
    }

    /**
     * Template Task Lists: get all template task lists
     *
     * @return Response
     * @throws Exception
     */
    public function getTemplates(): Response
    {
        return $this->fetch("$this->action/templates");
    }

    /**
     * Reorder Lists
     *
     * @param int $projectId
     * @param array $ids
     * @return bool
     */
    public function reorder(int $projectId, int ...$ids): bool
    {
        $params = [];
        foreach ($ids as $id) {
            $params[$this->parent][]['id'] = $id;
        }
        $parent = $this->parent . 's';
        $params = [$parent => $params];

        return $this->rest
            ->notUseFields()
            ->put(
                "projects/$projectId/$this->action/reorder",
                $params
            );
    }

    /**
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function create(object|array $data): int
    {
        $data = arr_obj($data);
        $projectId = $data->pull('project_id');
        $this->validates([
            'project_id' => $projectId
        ]);
        $apply_defaults_to_existing_tasks = $data->pull(
            'apply_defaults_to_existing_tasks'
        );
        $data = [
            $this->parent => $data
        ];
        if ($apply_defaults_to_existing_tasks !== null) {
            $data['apply_defaults_to_existing_tasks'] = $apply_defaults_to_existing_tasks;
        }

        return $this->rest
            ->post("projects/$projectId/$this->action", $data);
    }
}
