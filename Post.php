<?php

class TeamWorkPm_Post extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'title'=>true,
            'category_id'=>array('required'=>true, 'attributes'=>array('type'=>'integer')),
            'notify'=>array('required'=>false, 'attributes'=>array('type'=>'array'), 'element'=>'person'),
            'milestone_id'=>array('required'=>false, 'attributes'=>array('type'=>'integer')),
            'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'body'=>true
        );
    }

    /**
     * Retrieve Multiple Messages
     *
     * GET /projects/#{project_id}/posts.xml
     * For the project ID supplied, will return the 25 most recent messages
     *
     * Get archived messages
     *
     * GET /projects/#{project_id}/posts/archive.xml
     *
     * Rather than the full message, this returns a summary record for each message in the specified project.
     *
     * @param int $id
     * @param bool $archive
     * @return array|SimpleXMLElement
     */
    public function getByProjectId($id, $archive = false)
    {
        $action = "projects/$id/$this->_action";
        if ($archive) {
            $action .= '/archive';
        }
        return $this->_get($action);
    }
    
    /**
     * Retrieve Messages by Category
     *
     * GET /projects/#{project_id}/cat/#{category_id}/posts.xml
     *
     * As before, will return you the most recent 25 messages, this time limited by project and category.
     * 
     * Get archived messages by category

     * GET /projects/#{project_id}/cat/#{category_id}/posts/archive.xml

     * As above, but returns only the posts in the given category
     *
     * @param int $project_id
     * @param int $category_id
     * @param bool $archive
     * @return array|SimpleXMLElement
     */
    public function getByProjectAndCategoryId($project_id, $category_id, $archive = false)
    {
        $action = "projects/$project_id/cat/$category_id/$this->_action";
        if ($archive) {
            $action .= '/archive';
        }
        return $this->_get($action);
    }
}