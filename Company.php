<?php

class TeamWorkPm_Company extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_action = 'companies';
    }


    /**
     * Retrieve Companies
     *
     * GET /companies.xml
     *
     * The requesting user is returned a list of companies available to them.
     * 
     * @return array|SimpleXMLElement
     */
    public function getAll()
    {
        return $this->_get("$this->_action");
    }
    /**
     * Retrieving Companies within a Project
     *
     * GET /projects/#{project_id}/companies.xml
     *
     * All of the companies within the specified project are returned
     *
     * @param int $id
     * @return array|SimpleXMLElement
     */
    public function getByProjectId($id)
    {
        return $this->get("projects/$id/$this->_action");
    }

    public function  insert(array $data = array())
    {
        $this->_error(__METHOD__);
    }

    public function  update(array $data = array())
    {
        $this->_error(__METHOD__);
    }

    public function  delete($id = null)
    {
        $this->_error(__METHOD__);
    }

    private function _error($method)
    {
        throw new TeamWorkPm_Exception('Call to undefined method ' . $method);
    }

}