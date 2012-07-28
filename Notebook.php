<?php

class TeamWorkPm_Notebook extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'name' => TRUE,
            'description'=>TRUE,
            'content'=>TRUE,
            'notify'=>FALSE,
            'category_id'=>array('required'=>FALSE, 'attributes'=>array('type'=>'integer')),
            'category_name'=> FALSE,
            'private'=>array('required'=>FALSE, 'attributes'=>array('type'=>'boolean'))
        );
    }

    /**
     * List All Notebooks
     *
     * GET /notebooks.xml?includeContent=[true|false]
     *
     * Lists all notebooks on projects that the authenticated user is associated with.
     * By default, the actual notebook HTML content is not returned.
     * You can pass includeContent=true to return the notebook HTML content with the notebook data
     *
     * @return TeamWorkPm_Response_Model
     */
    public function getAll($include_content = FALSE)
    {
        return $this->_get("$this->_action", array(
          'includeContent'=>$include_content
        ));
    }

    /**
     * List Notebooks on a Project

     * GET /projects/#{project_id}/notebooks.xml

     * This lets you query the list of notebooks for a project.
     * By default, the actual notebook HTML content is not returned.
     * You can pass includeContent=true to return the notebook HTML content with the notebook data
     *
     * @param type $id
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($id, $include_content = FALSE)
    {
        $id = (int) $id;
        return $this->_get("projects/$id/$this->_action", array(
          'includeContent'=>$include_content
        ));
    }
    /**
     * Lock a Single Notebook For Editing
     *
     * PUT /notebooks/#{id}/lock.xml
     *
     * Locks the notebook and all versions for editing.
     *
     * @param type $id
     * @return bool
     */
    public function lock($id)
    {
        $id = (int) $id;
        return $this->_put("$this->_action/$id");
    }

    /**
     * Unlock a Single Notebook
     *
     * PUT /notebooks/#{id}/unlock.xml
     *
     * Unlocks a locked notebook so it can be edited again.
     *
     * @param type $id
     * @return bool
     */
    public function unlock($id)
    {
        $id = (int) $id;
        return $this->_put("$this->_action/$id");
    }
}