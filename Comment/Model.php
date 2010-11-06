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
    public function getRecent($resource_id, array $params = array('page'=>1, 'pageSize'=>20))
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        return $this->_get("$this->_resource/$resource_id/$this->_action", $params);
    }
}