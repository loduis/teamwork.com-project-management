<?php

class TeamWorkPm_Message extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'title'                    => TRUE,
            'category_id'              => array('required'=>TRUE, 'attributes'=>array('type'=>'integer')),
            'notify'                   => array(
                'required'=>FALSE,
                'attributes'=>array('type'=>'array'),
                'element'=>'person'
            ),
            'milestone_id'             => array('required'=>FALSE, 'attributes'=>array('type'=>'integer')),
            'private'                  => array('required'=>FALSE, 'attributes'=>array('type'=>'boolean')),
            'body'                     => TRUE,
            'attachments'              => FALSE,
            'pending_file_attachments' => FALSE
        );
        $this->_parent = 'post';
        $this->_action = 'posts';
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
    public function getByProject($id, $archive = FALSE)
    {
        $id  = (int) $id;
        if ($id <= 0) {
            throw new TeamWorkPm_Exception('Require parameter id');
        }
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
     * @return TeamWorkPm_Response_Model
     */
    public function getByProjectAndCategory($project_id, $category_id, $archive = FALSE)
    {
        $project_id  = (int) $project_id;
        if ($project_id <= 0) {
            throw new TeamWorkPm_Exception('Require parameter project_id');
        }
        $category_id  = (int) $category_id;
        if ($category_id <= 0) {
            throw new TeamWorkPm_Exception('Require parameter category_id');
        }
        $action = "projects/$project_id/cat/$category_id/$this->_action";
        if ($archive) {
            $action .= '/archive';
        }
        return $this->_get($action);
    }

    /**
     * Create a message
     *
     * POST /projects/#{project_id}/posts.xml
     *
     * This will create a new message.
     * Also, you have the option of sending a notification to a list of people you specify.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new TeamWorkPm_Exception('Require field project id');
        }
        return $this->_post("projects/$project_id/$this->_action", $data);
    }
}