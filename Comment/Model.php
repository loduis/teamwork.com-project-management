<?php

abstract class TeamWorkPm_Comment_Model extends TeamWorkPm_Model
{
    protected $_resource;

    protected function _init()
    {
        $this->_parent = 'comment';
        $this->_action = $this->_parent . 's';
        $this->_fields = array('body'=>true);
    }
    /**
     * Creating a Comment
     *
     * POST /#{resource}/#{resource_id}/comments.xml
     *
     *  Creates a new comment, associated with the particular resource.
     * When named in the URL, it can be either posts, todo_items or milestones.
     *
     * @param array $data
     * @return array|SimpleXMLElement
     */
    public function insert(array $data)
    {
        $resource_id = $data['resource_id'];
        if (empty($resource_id)) {
            throw new TeamWorkPm_Exception('Require field resource id');
        }
        return $this->_post("$this->_resource/$resource_id/$this->_action", $data);
    }
    /**
     *
     * @param int $resource_id
     * @param array $params [page, pageSize] this is only posible values
     * @return array|SimpleXmlElement
     */
    public function getRecent($resource_id, array $params = array())
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        foreach ($params as $name=>$value) {
            if (!in_array(strtolower($name), array('page', 'pagesize'))) {
                unset ($params[$name]);
            }
        }
        return $this->_get("$this->_resource/$resource_id/$this->_action", $params);
    }
}