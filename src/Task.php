<?php namespace TeamWorkPm;

class Task extends Model
{
    protected function init()
    {
        $this->fields = [
            'content'=>true,
            'notify'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ],
            'description'=>false,
            'due_date'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'integer'
                ]
            ],
            'start_date'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'integer'
                ]
            ],
            'private'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ],
            'priority'=>[
                'required'=>false,
                'validate'=>[
                    'low',
                    'medium',
                    'high'
                ]
            ],
            'estimated_minutes'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'integer'
                ]
            ],
            'responsible_party_id'     => false,
            'attachments'              => false,
            'pending_file_attachments' => false
        ];
        $this->parent = 'todo-item';
        $this->action = 'todo_items';
   }

    public function get($id, $get_time = false)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $params = [];
        if ($get_time) {
            $params['getTime'] = (int) $get_time;
        }
        return $this->rest->get("$this->action/$id", $params);
    }


    /**
     * Retrieve all tasks on a task list
     *
     * GET /todo_lists/#{todo_list_id}/tasks.json
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * If you want to return details about who created each todo item, you must
     * pass the flag "getCreator=yes". This will then return "creator-id",
     * "creator-firstname", "creator-lastname" and "creator-avatar-url" for each task.
     * A flag "canEdit" is returned with each task.
     *
     * @param int $task_list_id
     * @param mixed $params
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getByTaskList($task_list_id, $filter = 'all')
    {
        $task_list_id = (int) $task_list_id;
        if ($task_list_id <= 0) {
            throw new Exception('Invalid param task_list_id');
        }
        $params = [
            'filter'=> $filter
        ];
        $filter = strtolower($filter);
        $validate = ['all', 'pending', 'upcoming','late','today','finished'];
        if (in_array($filter, $validate)) {
            $params['filter'] = 'all';
        }
        return $this->rest->get("todo_lists/$task_list_id/$this->action", $params);
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
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $task_list_id = empty($data['task_list_id']) ? 0 : (int) $data['task_list_id'];
        if ($task_list_id <= 0) {
            throw new Exception('Required field task_list_id');
        }
        if (!empty($data['files'])) {
            $file = \TeamWorkPm\Factory::build('file');
            $data['pending_file_attachments'] = $file->upload($data['files']);
            unset($data['files']);
        }
        return $this->rest->post("todo_lists/$task_list_id/$this->action", $data);
    }

    /**
     * Mark an Item Complete
     *
     * PUT /todo_items/#{id}/complete.xml
     *
     * The submitted todo item is marked as complete
     *
     * @param int $id
     * @return bool
     */
    public function complete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->put("$this->action/$id/complete");
    }

    /**
     * Mark an Item Uncomplete
     *
     * PUT /todo_items/#{id}/uncomplete.xml
     *
     * Changes the item to uncomplete. (if called on an uncomplete item, has no effect)
     *
     * @param int $id
     * @return bool
     */
    public function uncomplete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }

        return $this->rest->put("$this->action/$id/uncomplete");
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
     * @return bool
     */
    public function reorder($task_list_id, array $ids)
    {
        $task_list_id = (int) $task_list_id;
        if ($task_list_id <= 0) {
            throw new Exception('Invalid param task_list_id');
        }
        return $this->rest->post("todo_lists/$task_list_id/" .
                                                "$this->action/reorder", $ids);
    }
}