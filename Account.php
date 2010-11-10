<?php

class TeamWorkPm_Account extends TeamWorkPm_Model
{
    /**
     * Get Account Details
     * GET /account.xml
     *
     * Retrieves details about the Teamwork account. A nice about this is call is that it returns "cacheuuid"
     * which is a string that you can use to quickly determine if the application has been updated since you
     * last accessed it.
     * 
     * @return array|SimpleXMLElement
     */
    public function get($id = null)
    {
        return $this->_get("$this->_parent");
    }

    /**
     * The 'Authenticate' Call
     *
     * GET /authenticate.xml
     *
     * Returns details about the company account.
     * The unique thing about this call is that it will return the details for the users installation even
     * if you any *.teamworkpm.net URL eg. Call "http://query.teamworkpm.net/authenticate.xml" will work!
     * You can use this to require users to only have to enter their API key and nothing else - clever eh!.
     *
     * If it fails, you get a standard failure response.
     *
     * @return array|SimpleXMLElement
     */
    public function getAuthenticate()
    {
        return $this->_get('authenticate');
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