<?php

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
     *
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
     *
     * @return Response
     * @throws Exception
     */
    public function getByTaskList($id, array|object $params = []): Response
    {
        return $this->fetch("tasklists/$id/$this->action", $params);
    }

    /**
     * Get all Tasks on a given Task List
     *
     * @param int $id
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject($id, array|object $params = []): Response
    {
        return $this->fetch("projects/$id/$this->action", $params);
    }

    /**
     * Create Item on a List
     *
     * POST /todo_lists/#{todo_list_id}/todo_items.xml
     *
     * For the submitted list, creates a todo item. It will be added to the end of the list,
     * and marked as uncomplete by default. If you give a persons id as the responsible-party-id value,
     * they will be responsible for same, you can also use the “notify” key to indicate whether an email
     * should be sent to that person to tell them about the assignment.
     * Multiple people can be assigned by passing a comma delimited list for responsible-party-id.
     *
     * @param array|object $data
     *
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
                ->upload($files->toArray());
        }

        return $this->post("$root/$id/$this->action", $data);
    }

    /**
     * Mark an Item Complete
     *
     * @param int $id
     *
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
     * PUT /todo_items/#{id}/uncomplete.xml
     *
     * Changes the item to uncomplete. (if called on an uncomplete item, has no effect)
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function unComplete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }

        return $this->put("$this->action/$id/uncomplete");
    }

    /**
     * Reorder the todo items
     *
     * POST /todo_lists/#{todo_list_id}/todo_items/reorder.xml
     *
     * Re-orders items on the submitted list. Completed items cannot be reordered,
     * and any items not specified will be sorted after the items explicitly given
     * Items can be re-parented by putting them from one list into the ordering of items for a different list/
     *
     * @param int $task_list_id
     * @param array $ids
     *
     * @return bool
     * @throws Exception
     */
    public function reorder($task_list_id, array $ids)
    {
        $task_list_id = (int)$task_list_id;
        if ($task_list_id <= 0) {
            throw new Exception('Invalid param task_list_id');
        }
        return $this->post("todo_lists/$task_list_id/$this->action/reorder", $ids);
    }
}
