<?php
namespace TeamWorkPm;

class Notebook extends Model
{

    protected function _init()
    {
        $this->_fields = array(
            'name' => true,
            'description'=>true,
            'content'=>true,
            'notify'=>false,
            'category_id'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'category_name'=> false,
            'private'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            )
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
     * @return TeamWorkPm\Response\Model
     */
    public function getAll($include_content = false)
    {
        return $this->rest->get("$this->_action", array(
          'includeContent'=>$include_content
        ));
    }

    /**
     * List Notebooks on a Project
     *
     * GET /projects/#{project_id}/notebooks.xml
     *
     * This lets you query the list of notebooks for a project.
     * By default, the actual notebook HTML content is not returned.
     * You can pass includeContent=true to return the notebook HTML content with the notebook data
     *
     * @param type $id
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($id, $include_content = false)
    {
        $id = (int) $id;
        return $this->rest->get("projects/$id/$this->_action", array(
          'includeContent'=>$include_content
        ));
    }
    /**
     * Lock a Single Notebook For Editing
     *
     * PUT /notebooks/#{id}/lock
     *
     * Locks the notebook and all versions for editing.
     *
     * @param type $id
     * @return bool
     */
    public function lock($id)
    {
        $id = (int) $id;
        return $this->rest->put("$this->_action/$id/lock");
    }

    /**
     * Unlock a Single Notebook
     *
     * PUT /notebooks/#{id}/unlock
     *
     * Unlocks a locked notebook so it can be edited again.
     *
     * @param type $id
     * @return bool
     */
    public function unlock($id)
    {
        $id = (int) $id;
        return $this->rest->put("$this->_action/$id/unlock");
    }

    /**
     * Create a Single Notebook
     *
     * POST /projects/#{project_id}/notebooks
     * This command will create a single notebook.
     * Content must be valid XHMTL
     * You not not need to include <html>, <head> or <body> tags
     */
    public function insert(array $data)
    {
        $project_id = (int) empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception('Require field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->_action", $data);
    }

    public function update(array $data)
    {
        throw new \TeamWorkPm\Exception('Not method supported');
    }
}
