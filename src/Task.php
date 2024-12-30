<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\CompleteTrait;
use TeamWorkPm\Rest\Resource\Project\GetByTrait as GetByProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/tasks/get-tasks-json
 */
class Task extends Model
{
    use CompleteTrait, GetByProjectTrait;

    protected ?string $parent = 'todo-item';

    protected ?string $action = 'tasks';

    protected string|array $fields = 'tasks';

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
     * Create a Time-Entry (for a Task)
     *
     * @param integer $id
     * @param array|object $data
     * @return integer
     */
    public function addTime(int $id, array|object $data): int
    {
        $data = arr_obj($data);
        $data['task_id'] = $id;

        return Factory::time()->create($data);
    }

    /**
     * Total Time on a Task
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getTotalTime(int $id, array|object $params = []): Response
    {
        return $this->fetch("$this->action/$id/time/total", $params);
    }

    /**
     * Retrieve all Task times
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getTimes(int $id): Response
    {
        return Factory::time()->getByTask($id);
    }

    /**
     * Add a Time estimate to a Task
     *
     * @param int $id
     * @param int $minutes
     * @return bool
     * @throws Exception
     */
    public function addEstimateTime(int $id, int $minutes): bool
    {
        return $this
        ->notUseFields()
        ->put("$this->action/$id/estimatedtime", [
            'taskEstimatedMinutes' => $minutes
        ]);
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

        if ($projectId && $taskListId) {
            throw new Exception('Only one field task_list_id or project_id');
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

    /**
     * Move a Task from one Project to Another
     *
     * @param integer $id
     * @param integer $projectId
     * @param integer $taskListId
     * @return Response
     */
    public function move(int $id, int $projectId, int $taskListId): Response
    {
        return $this->notUseFields()
            ->put(
                "$this->action/$id/move",
                compact('projectId', 'taskListId')
        );
    }

    /**
     * Copy a Task from one Project to Another
     *
     * @param integer $id
     * @param integer $projectId
     * @param integer $taskListId
     * @return int
     */
    public function copy(int $id, int $projectId, int $taskListId): int
    {
        return $this->notUseFields()
            ->put(
                "$this->action/$id/copy",
                compact('projectId', 'taskListId')
        );
    }
}
