<?php namespace TeamWorkPm\People;

class Status extends \TeamWorkPm\Rest\Model
{
    protected function init()
    {
        $this->parent = 'userstatus';
        $this->action = 'status';
        $this->fields = [
          'status'=>true,
          'notify'=>false
        ];
    }

    /**
     * Retrieve a Persons Status
     *
     * GET /people/#{person_id}/status
     * Returns the latest status post for a user
     *
     * @param type $id
     * @return TeamWorkPm\Response\Model
     */
    public function get($person_id)
    {
        $person_id = (int) $person_id;
        return $this->rest->get("people/$person_id/$this->action");
    }

    /**
     * Retrieve Everybodys Status
     * GET /people/status
     * All of the latest status posts are returned for all users in the parent company.
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getAll()
    {
        return $this->rest->get("people/$this->action");
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
            throw new \TeamWorkPm\Exception('Required field person_id');
        }
        unset($data['person_id']);

        return $this->rest->post("people/$person_id/$this->action", $data);
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
            throw new \TeamWorkPm\Exception('Required field id');
        }
        $person_id = empty($data['person_id']) ? 0 : (int) $data['person_id'];
        unset($data['id'], $data['person_id']);
        return $this->rest->put('people/' .
           ($person_id ? $person_id . '/' : '') .  "$this->action/$id", $data);
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
    public function delete($id, $person_id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new  \TeamWorkPm\Exception('Invalid param id');
        }
        return $this->rest->delete('people/' .
            ($person_id ? $person_id . '/' : '') .  "$this->action/$id");
    }

    /**
     *
     * @param array $data
     * @return bool|int
     */
    final public function save(array $data)
    {
        return array_key_exists('id', $data) ?
            $this->update($data) :
            $this->insert($data);
    }
}