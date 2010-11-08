<?php

class TeamWorkPm_Project extends TeamWorkPm_Model
{
    /**
     * Retrieves all accessible projects; including active/inactive and archived projects.
     * You can optionally append a date to the call to return only those projects recently updated.
     * This is very useful if you are implementing local caching as you won't have to recheck
     * everything therefore making your applicaton much faster. You can pass in a date and/or a date
     * with a time using the variables updatedAfterDate and updatedAfterTime.
     * @return array|SimpleXMLElement
     */
    public function getAll($date = null, $time = null)
    {
        $params = array();
        if (!is_null($date)) {
            $params['updatedAfterDate'] = $date;
            if (!is_null($time)) {
                $params['updatedAfterTime'] = $time;
            }
        }

        return $this->_get("$this->_action", $params);
    }

    /**
     * Surprisingly, this will retrieve all of your projects, which have been starred!
     * @return array|SimpleXMLElement
     */
    public function getStarred()
    {
        return $this->_get("$this->_action/starred");
    }
    /**
     * Adds a project to your list of favourite projects.
     * @param int $id
     * @return bool
     */
    public function star($id)
    {
        return $this->_put("$this->_action/$id/star");
    }
    /**
     * Removes a project from your list of favourite projects.
     * @param int $id
     * @return bool
     */
    public function unStar($id)
    {
        return $this->_put("$this->_action/$id/unstar");
    }

    public function  insert(array $data = array())
    {
        throw new TeamWorkPm_Exception('Call to undefined method ' . __METHOD__);
    }

    public function  update(array $data = array())
    {
        throw new TeamWorkPm_Exception('Call to undefined method ' . __METHOD__);
    }

    public function  delete($id = null)
    {
        throw new TeamWorkPm_Exception('Call to undefined method ' . __METHOD__);
    }
}