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
    /**
     * GET /todo_lists.xml?responsible_party_id=#{id}

     * Retrieves all todo-lists, with records assigned to the appropriate person.
     * If no-one is assigned, the current user will be assumed.
     * The 'resposible-party-id' parameter can be changed to be blank (unassigned),
     * to a persons-ID, or a company id [prefixed with c]. You can further filter these
     * results with the 'filter' query. You can set this to
     * 'all', 'pending', 'late' and 'finished'. 'pending' lists incomplete tasks.
     * 
     * @param mixed $id
     * @param string $filer
     */
    public function getByPersonId($id = null, $filter = 'all')
    {
        return $this->_get("$this->_action", array(
           'responsible_party_id'=>$id,
           'filter'=>$filter
        ));
    }

    public function getByProjectId($id, $filter = 'all')
    {
        return $this->_get("projects/$id/todo_lists", array('filter'=>$filter));
    }
   
    public function reorder($project_id, array $ids)
    {
        return $this->_post("projects/$project_id/todo_lists/reorder", $ids);
    }
}