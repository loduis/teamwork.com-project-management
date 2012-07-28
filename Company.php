<?php

class TeamWorkPm_Company extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'=>TRUE,
            'address_one'=>FALSE,
            'address_two'=>FALSE,
            'zip'=>FALSE,
            'city'=>FALSE,
            'state'=>FALSE,
            'countrycode'=>FALSE,
            'phone'=>FALSE,
            'fax'=>FALSE,
            'web_address'=>FALSE
        );
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
     * @return TeamWorkPm_Response_Model
     */
    public function getAllByProject($id)
    {
        return $this->_get("projects/$id/$this->_action");
    }
}