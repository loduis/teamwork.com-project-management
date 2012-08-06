<?php

class TeamWorkPm_Project extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'=>TRUE,
            'description'=>FALSE,
            'start_date'=>FALSE,
            'end_date'=>FALSE,
            'company_id'=>FALSE,
            'new_company'=>FALSE,
            'status'=>FALSE
        );
    }

    /**
     * Retrieves all accessible projects; including active/inactive and archived projects.
     * You can optionally append a date to the call to return only those projects recently updated.
     * This is very useful if you are implementing local caching as you won't have to recheck
     * everything therefore making your applicaton much faster. You can pass in a date and/or a date
     * with a time using the variables updatedAfterDate and updatedAfterTime.
     * @return TeamWorkPm_Response_Model
     */
    public function getAll($updatedAfterDate = NULL, $updatedAfterTime = NULL)
    {
        return $this->_getByStatus('all', $updatedAfterDate, $updatedAfterTime);

    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return TeamWorkPm_Response_Model
     */
    public function getActive($updatedAfterDate = NULL, $updatedAfterTime = NULL)
    {
        return $this->_getByStatus('active', $updatedAfterDate, $updatedAfterTime);
    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return TeamWorkPm_Response_Model
     */
    public function getArchived($updatedAfterDate = NULL, $updatedAfterTime = NULL)
    {
        return $this->_getByStatus('archived', $updatedAfterDate, $updatedAfterTime);
    }

    /**
     *
     * @param type $status
     * @param type $date
     * @param type $time
     * @return type
     */
    private function _getByStatus($status, $date, $time)
    {
        $params = array();
        if ($date !== NULL) {
            $params['updatedAfterDate'] = $date;
            if ($time !== NULL) {
                $params['updatedAfterTime'] = $time;
            }
        }
        $params['status'] = $status;
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
        $id = (int) $id;
        if (empty($id)) {
            throw new TeamWorkPm_Exception('Required field id');
        }
        return $this->_put("$this->_action/$id/star");
    }

    /**
     * Removes a project from your list of favourite projects.
     * @param int $id
     * @return bool
     */
    public function unStar($id)
    {
        $id = (int) $id;
        if (empty($id)) {
            throw new TeamWorkPm_Exception('Required field id');
        }
        return $this->_put("$this->_action/$id/unstar");
    }

    /**
     * Shortcut for active project
     *
     * @param type $id
     * @return bool
     */
    public function active($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'active';
        return $this->update($data);
    }

    /**
     * Shortcut for archive project
     *
     * @param type $id
     * @return bool
     */
    public function archived($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'archived';
        return $this->update($data);
    }
}