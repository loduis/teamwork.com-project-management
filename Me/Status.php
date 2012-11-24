<?php
namespace TeamWorkPm\Me;

class Status extends \TeamWorkPm\Rest\Model
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
     * GET /me/status
     *
     * Returns the latest status post for a user
     *
     * @param type $id
     * @return TeamWorkPm\Response\Model
     */
    public function get()
    {
        return $this->rest->get("me/$this->_action");
    }

    /**
     * Create Status
     *
     * POST /me/status
     *
     * This call will create a status entry. The Id of the new status is returned in header "id".
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->rest->post("me/$this->_action", $data);
    }

    /**
     * Update Status
     *
     * PUT /me/status/#{status_id}
     *
     * Modifies a status post.
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $id = (int) empty($data['id']) ? 0 : $data['id'];
        if ($id <= 0) {
            throw new \TeamWorkPm\Exception('Require field id');
        }
        unset($data['id']);
        return $this->rest->put("me/$this->_action/$id", $data);
    }

    /**
     * Delete Status
     *
     * DELETE /me/status/#{status_id}
     *
     * This call will delete a particular status message.
     * Returns HTTP status code 200 on success.
     *
     * @param int $id
     * @param int $person_id optional
     * @return bool
     */
    public function delete($id)
    {
        return $this->rest->delete("me/$this->_action/$id");
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