<?php

namespace TeamWorkPm;

class Role extends Model
{
    protected function init()
    {
        static::$fields = [
            'name' => true,
            'description' => false,
            'users' => [
                'type' => 'string',
            ],
        ];
        $this->action = 'roles';
    }

    /**
     * Retrieve a user role.
     *
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get($id, $params = null)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * Get all Roles (within a Project)
     * GET /projects/#{project_id}/roles
     * Retrieves all the roles in a given project
     *
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     */
    public function getByProject($id)
    {
        $id = (int)$id;
        return $this->rest->get("projects/$id/$this->action");
    }

    /**
     * Create a role
     * POST /projects/#{project_id}/roles.xml
     * This will create a new role.
     *
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function create(array $data)
    {
        $project_id = empty($data['project_id']) ? 0 : (int)$data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->action", $data);
    }

    /**
     * Update Role
     * PUT /roles/#{role_id}
     * Modifies an existing role.
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
        unset($data['id']);
        return $this->rest->put("$this->action/$id", $data);
    }
}
