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

    public function getByTodoListId($id)
    {
        return $this->_get("todo_lists/$id/$this->_action");
    }

    public function insert(array $data)
    {
        $todo_list_id = $data['todo_list_id'];
        if (empty($todo_list_id)) {
            throw new TeamWorkPm_Exception('Require field todo list id');
        }
        return $this->_post("todo_lists/$todo_list_id/$this->_action", $data);
    }

    public function markAsComplete($id)
    {
        return $this->_put("$this->_action/$id/complete");
    }

    public function markAsUnComplete($id)
    {
        return $this->_put("$this->_action/$id/uncomplete");
    }

    public function reorder($todo_list_id, array $ids)
    {
        return $this->_post("todo_lists/$todo_list_id/$this->_action/reorder", $ids);
    }
}