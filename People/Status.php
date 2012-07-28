<?php

class TeamWorkPm_People_Status extends TeamWorkPm_Rest_Model
{
    protected function _init()
    {
        $this->_parent = 'userstatus';
        $this->_action = 'status';
        $this->_fields = array(
          'status'=>TRUE,
          'notify'=>FALSE
        );
    }

    /**
     * Retrieve a Persons Status
     *
     * GET /people/statuses/#{status_id}
     * Returns the latest status post for a user
     *
     * @param type $id
     * @return TeamWorkPm_Response_Model
     */
    public function get($id)
    {
        $id = (int) $id;
        return $this->_get("people/statuses/$id");
    }

    /**
     * Retrieve Everybodys Status
     * GET /people/status.xml
     * All of the latest status posts are returned for all users in the parent company.
     *
     * @return TeamWorkPm_Response_Model
     */
    public function getAll()
    {
        return $this->_get("people/$this->_action");
    }

    /**
     * Create Status
     * POST /people/#{person_id}/status
     * This call will create a status entry. The Id of the new status is returned in header "id".
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $person_id = empty($data['person_id']) ? 0 : (int) $data['person_id'];
        if ($person_id <= 0) {
            throw new TeamWorkPm_Exception('Required field person_id');
        }
        unset($data['person_id']);

        $this->_post("people/$person_id/$this->_action", $data);
    }

    /**
     *   Update Status
     *   PUT /people/status/#{status_id}.xml
     *   PUT /people/#{person_id}/status/#{status_id}.xml
     *   Modifies a status post.
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $id = (int) empty($data['id']) ? 0 : $data['id'];
        if ($id <= 0) {
            throw new TeamWorkPm_Exception('Require field id');
        }
        $person_id = empty($data['person_id']) ? 0 : (int) $data['person_id'];
        unset($data['id'], $data['person_id']);
        return $this->_put('people/' . ($person_id ? $person_id . '/' : '') .  "$this->_action/$id", $data);
    }

    /**
     * Delete Status
     *
     * DELETE /people/status/#{status_id}
     * DELETE /people/#{person_id}/status/#{status_id}
     *
     * This call will delete a particular status message.
     * Returns HTTP status code 200 on success.
     *
     * @param int $id
     * @param int $person_id optional
     * @return bool
     */
    public function delete($id, $person_id = NULL)
    {
        return $this->_delete('people/' . ($person_id ? $person_id . '/' : '') .  "$this->_action/$id");
    }

    /**
     *
     * @param array $data
     * @return bool|int
     */
    final public function save(array $data)
    {
        return !empty($data['id']) ?
            $this->update($data) :
            $this->insert($data);
    }
}