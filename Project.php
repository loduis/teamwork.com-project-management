<?php

class TeamWorkPm_Project extends TeamWorkPm_Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'=>TRUE,
            'description'=>FALSE,
            'start-date'=>FALSE,
            'end-date'=>FALSE,
            'companyId'=>FALSE,
            'newCompany'=>FALSE,
            'status'=>FALSE
        );
    }
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
        return $this->_getByStatus('ALL', $date, $time);

    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return type
     */
    public function getActive($date = null, $time = null)
    {
        return $this->_getByStatus('ACTIVE', $date, $time);
    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return type
     */
    public function getArchived($date = null, $time = null)
    {
        return $this->_getByStatus('ARCHIVED', $date, $time);
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
     * Insert a project
     *
     * @param array $data
     * @return bool
     */
    public function  insert(array $data)
    {
        return $this->_post($this->_action, $data);
    }

    public function active($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'ACTIVE';
        return $this->update($data);
    }

    public function archive($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'ARCHIVED';
        return $this->update($data);
    }
}