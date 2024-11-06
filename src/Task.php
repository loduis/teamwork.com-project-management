<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/tasks/get-tasks-json
 */
class Task extends Model
{
    protected ?string $parent = 'todo-item';

    protected ?string $action = 'tasks';

    protected string|array $fields = 'tasks';

    /**
     * Get all Tasks across all Projects
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
     * Get all Tasks on a given Task List
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByTaskList(int $id, array|object $params = []): Response
    {
        return $this->fetch("tasklists/$id/$this->action", $params);
    }

    /**
     * Get all Tasks on a given Task List
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, array|object $params = []): Response
    {
        return $this->fetch("projects/$id/$this->action", $params);
    }

    /**
     * Retrieve Task Dependencies
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getDependencies(int $id): Response
    {
        return $this->fetch("$this->action/$id/dependencies");
    }

    /**
     * Get completed Tasks
     *
     * @todo Need check
     *
     * @return Response
     * @throws Exception
     */
    public function getCompleted(): Response
    {
        return $this->fetch("completedtasks");
    }

    /**
     * Get Task Followers
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getFollowers(int $id): Response
    {
        return $this->fetch("$this->action/$id/followers");
    }

    /**
     * Get Task Predecessors
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getPredecessors(int $id): Response
    {
        return $this->fetch("$this->action/$id/predecessors");
    }

    /**
     * Get Sub Tasks of a Task
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getSubTasks(int $id): Response
    {
        return $this->fetch("$this->action/$id/subtasks");
    }

    /**
     * Get Recurring Tasks related to original Task.
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getRecurring(int $id, array|object $params = []): Response
    {
        return $this->fetch("$this->action/$id/recurring", $params);
    }

    /**
     * Create a Task on a Project
     * Create a Task on a TaskList
     *
     * @param array|object $data
     * @return int
     * @throws Exception
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $taskListId = $data->pull('task_list_id');
        $projectId = $data->pull('project_id');
        if ($projectId && $taskListId) {
            $data['task_list_id'] = $taskListId;
        }

        if (!($projectId || $taskListId)) {
            throw new Exception('Required field task_list_id or project_id');
        }

        $root = $projectId ? 'projects' : 'tasklists';
        $id = $projectId ? $projectId : $taskListId;
        $files = $data->pull('files');
        if ($files !== null) {
            $data['pending_file_attachments'] = Factory::file()
                ->upload($files);
        }

        return $this->post("$root/$id/$this->action", $data);
    }

    /**
     * Create a Sub Task
     *
     * @param integer $id
     * @param array|object $data
     * @return integer
     */
    public function add(int $id, array|object $data): int
    {
        $data = arr_obj($data);
        $data->pull('project_id');
        $data->pull('task_list_id');
        $files = $data->pull('files');
        if ($files !== null) {
            $data['pending_file_attachments'] = Factory::file()
                ->upload($files);
        }

        return $this->post("$this->action/$id", $data);
    }

    /**
     * Mark a Task complete
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function complete(int $id): bool
    {
        return $this->put("$this->action/$id/complete");
    }

    /**
     * Mark an Item Uncomplete
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function unComplete(int $id): bool
    {
        return $this->put("$this->action/$id/uncomplete");
    }

    /**
     * Reorder the Tasks
     *
     * @param int $id
     * @param array $ids
     * @return bool
     * @throws Exception
     */
    public function reorder(int $id, int ...$ids)
    {
        $params = [];
        foreach ($ids as $task) {
            $params[$this->parent][]['id'] = $task;
        }
        $parent = $this->parent . 's';
        $params = [$parent => $params];

        return $this->put("tasklists/$id/$this->action/reorder", $ids);
    }
}
