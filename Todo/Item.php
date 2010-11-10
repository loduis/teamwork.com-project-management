<?php

class TeamWorkPm_Todo_Item extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'content'=>true,
            'notify'=>array('required'=>false, 'attributes'=>array('type'=>'boolean=false')),
            'description'=>false,
            'due_date'=>array('required'=>false, 'attributes'=>array('type'=>'integer')),
            'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'priority'=>array('required'=>false, 'attributes'=>array('type'=>'integer')),
            'responsible_party_id'=>false
        );
    }
    /**
     * Retrieve All Items on a Todo List
     *
     * GET /todo_lists/#{todo_list_id}/todo_items.xml
     *
     *  This is almost the same as the “Get list” action, except it does only returns the items.
     *
     * @param int $id
     * @return array|SimpleXMLElement
     */
    public function getByTodoListId($id)
    {
        return $this->_get("todo_lists/$id/$this->_action");
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
     * @return bool
     */
    public function insert(array $data)
    {
        $todo_list_id = $data['todo_list_id'];
        if (empty($todo_list_id)) {
            throw new TeamWorkPm_Exception('Require field todo list id');
        }
        return $this->_post("todo_lists/$todo_list_id/$this->_action", $data);
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
    public function markAsComplete($id)
    {
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
    public function markAsUnComplete($id)
    {
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
     * @param int $todo_list_id
     * @param array $ids
     * @return bool
     */
    public function reorder($todo_list_id, array $ids)
    {
        return $this->_post("todo_lists/$todo_list_id/$this->_action/reorder", $ids);
    }
}