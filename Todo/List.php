<?php

class TeamWorkPm_Todo_List extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'=>true,
            'todo_list_template_id'=>false,
            'milestone_id'=>false,
            'description'=>false,
            'todo_list_template_task_date'=>false,
            'tracked'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'todo_list_template_task_assignto'=>false
        );
    }

    public function getByProjectId($id, $filter = 'all')
    {
        if (is_numeric($id)) {
            return $this->_get("projects/$id/todo_lists", array('filter'=>$filter));
        }
        return null;
    }
   
    public function reorder($project_id, array $ids)
    {
        return $this->_post("projects/$project_id/todo_lists/reorder", $ids);
    }
}