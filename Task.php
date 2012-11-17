<?php
namespace TeamWorkPm;

class Task extends Model
{
    protected function _init()
    {
        $this->_fields = array(
            'content'=>true,
            'notify'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'description'=>false,
            'due_date'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'start_date'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'private'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'priority'=>array(
                'required'=>false,
                'validate'=>array(
                    'low',
                    'medium',
                    'high'
                )
            ),
            'estimated_minutes'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'responsible_party_id'     => false,
            'attachments'              => false,
            'pending_file_attachments' => false
        );
        $this->_parent = 'todo-item';
        $this->_action = 'todo_items';
   }

    /**
     * Retrieve All Items on a Todo List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml?filter=all
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * If you want to return details about who created each todo item, you must
     * pass the flag "getCreator=yes". This will then return "creator-id",
     * "creator-firstname", "creator-lastname" and "creator-avatar-url" for each task.
     * A flag "canEdit" is returned with each task.
     *
     * @param int $id
     * @param array $params Filter parameters
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getAllByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'all', $params);
    }

    /**
     * Retrieve All pending Items on a Task List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml?filter=penging
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * @param int $id
     * @param array $params Filter parameters
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getPendingByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'pending', $params);
    }

    /**
     * Retrieve All upcoming Items on a Task List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml?filter=upcoming
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * @param int $id
     * @param array $params Filter parameters
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getUpcomingByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'upcoming', $params);
    }

    /**
     * Retrieve All finished tasks on a Task List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml?filter=finished
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * @param int $id
     * @param array $params Filter parameters
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getFinishedByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'finished', $params);
    }

    /**
     * Retrieve All late tasks on a Task List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml?filter=late
     *
     * This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * @param int $id
     * @param array $params Filter parameters
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getLateByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'late', $params);
    }

    public function getTodayByTaskList($id, $params = array())
    {
        return $this->_getByFilter($id, 'today', $params);
    }

    private function _getByFilter($id, $filter, $params)
    {
        $params['filter'] = $filter;
        $id = (int) $id;
        return $this->_get("todo_lists/$id/$this->_action", $params);
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
        $task_list_id = (int) empty($data['task_list_id']) ? 0 : $data['task_list_id'];
        if ($task_list_id <= 0) {
            throw new Exception('Require field todo list id');
        }
        return $this->_post("todo_lists/$task_list_id/$this->_action", $data);
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
        return $this->_put("$this->_action/$id/complete");
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
    public function unComplete($id)
    {
      $id = (int) $id;
      return $this->_put("$this->_action/$id/uncomplete");
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
        return $this->_post("todo_lists/$task_list_id/$this->_action/reorder", $ids);
    }
}