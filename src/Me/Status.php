<?php

namespace TeamWorkPm\Me;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;

class Status extends Resource
{
    protected function init()
    {
        $this->parent = 'userstatus';
        $this->action = 'status';
        $this->fields = [
            'status' => true,
            'notify' => false,
        ];
    }

    /**
     * Retrieve a Persons Status
     *
     * GET /me/status
     *
     * Returns the latest status post for a user
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get()
    {
        return $this->fetch("me/$this->action");
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
    public function create(array $data)
    {
        return $this->post("me/$this->action", $data);
    }

    /**
     * Update Status
     *
     * PUT /me/status/#{status_id}
     *
     * Modifies a status post.
     *
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array $data)
    {
        $id = empty($data['id']) ? 0 : (int)$data['id'];
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->put("me/$this->action/$id", $data);
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
     *
     * @return bool
     * @throws Exception
     */
    public function delete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->del("me/$this->action/$id");
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    final public function save(array $data)
    {
        return array_key_exists('id', $data)
            ? $this->update($data)
            : $this->insert($data);
    }
}
