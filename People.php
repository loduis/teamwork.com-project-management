<?php
namespace TeamWorkPm;

class People extends Model
{

    protected function _init()
    {
        $this->_fields = array(
            'first_name' => true,
            'last_name' => true,
            'email_address'=>false,
            'user_name' => true,
            'password' => true,
            'company_id' => false,
            'title' => false,
            'phone_number_mobile'=>false,
            'phone_number_office'=>false,
            'phone_number_office_ext'=>false,
            'phone_number_fax'=>false,
            'phone_number_home'=>false,
            'im_handle'=>false,
            'im_service'=>array(
                'required'=>false,
                'validate'=>array(
                    'GTalk',
                    'AOL',
                    'ICQ',
                    'MSN',
                    'Jabber',
                    'Yahoo',
                    'Skype',
                    'Twitter'
                )
            ),
            'date_format'=>array(
                'required'=>false,
                'validate'=>array(
                    'dd.mm.yyyy',
                    'dd/mm/yyyy',
                    'mm.dd.yyyy',
                    'mm/dd/yyyy',
                    'yyyy-mm-dd',
                    'yyyy.mm.dd'
                )
            ),
            'send_welcome_email'=>array(
                'required'=>false,
                'validate'=>array('yes', 'no')
            ),
            'receive_daily_reports'=>array(
                'required'=>false,
                'validate'=>array('yes', 'no')
            ),
            'welcome_email_message'=>false,
            'auto_give_project_access'=>array(
                'required'=>false,
                'validate'=>array('yes', 'no')
            ),
            'open_id'=>false,
            'notes'=>array(
                'required'=>false,
                'validate'=>array('yes', 'no')
            ),
            'user_language'=>array(
                'required'=>false,
                'validate'=>array(
                    'EN',
                    'FR',
                    'AR',
                    'BG',
                    'ZH',
                    'HR',
                    'CS',
                    'DA',
                    'NL',
                    'FI',
                    'DE',
                    'EL',
                    'HU',
                    'ID',
                    'IT',
                    'JA',
                    'KO',
                    'NO',
                    'PL',
                    'PT',
                    'RO',
                    'RU',
                    'ES',
                    'SV'
                )
            ),
            'administrator'=>false,
            'can_add_projects'=>array(
                'required'=>false,
                'validate'=>array('yes', 'no')
            )
        );
        $this->_parent = 'person';
        $this->_action = 'people';
    }

    /**
     * Get people
     * GET /people.xml
     * All people visible to the user will be returned, including the user themselves
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getAll()
    {
        return $this->rest->get($this->_action);
    }

    /**
     * Get all People (within a Project)
     * GET /projects/#{project_id}/people
     * Retrieves all of the people in a given project
     *
     * @param type $id
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        return $this->rest->get("projects/$id/$this->_action");
    }

    /**
     * Get People (within a Company)
     * GET /companies/#{company_id}/people
     * Retreives the details for all the people from the submitted company
     * (excluding those you don't have permission to see)
     *
     * @param int $id
     * @return TeamWorkPm\Response\Model
     */
    public function getByCompany($id)
    {
        $id = (int) $id;
        return $this->rest->get("companies/$id/$this->_action");
    }

    /**
     * Get a specific Person Permissions (within a Project)
     * GET /projects/#{project_id}/people/{person_id}.xml
     * Retrieves the details and permissions of a specific person on a given project
     *
     */
    public function getInProject($people_id, $project_id)
    {
        return $this->rest->get("projects/$project_id/people/$people_id");
    }

    /**
     * Add a new user to a project
     * POST /projects/{id}/people/{id}
     * Add a user to a project. Default permissions setup in Teamwork will be used.
     * Returns HTTP status code 201 (Created) on success.
     *
     * @param int $people_id
     * @param int $project_id
     *
     * @return bool
     */
    private function addToProject($people_id, $project_id)
    {
        $project_id = (int) $project_id;
        $people_id  = (int) $people_id;
        if ($project_id <= 0) {
            throw new Exception('Require parameter project_id');
        }
        if ($people_id <= 0) {
            throw new Exception('Require parameter people_id');
        }
        return $this->rest->post("projects/$project_id/people/$people_id");
    }

    /**
     * Add a new user
     * POST /people
     * Creates a new user account
     *
     * if an project_id is given to add this project
     *
     * if permissions is given update this
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) (isset($data['project_id']) ? $data['project_id'] : 0);
        $permissions = empty($data['permissions']) ? array() : $data['permissions'];
        unset ($data['permissions'], $data['project_id']);
        if ($id = parent::insert($data)) {
            if ($project_id > 0) {
                $data = array(
                    'permissions' => $permissions
                );
                $data['project_id'] = $project_id;
                $data['id']         = $id;
                $this->update($data);
            }
        }
        return $id;
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $id = (int) empty($data['id']) ? 0 : $data['id'];
        if ($id <= 0) {
            throw new Exception('Require field id');
        }
        $permissions = empty($data['permissions']) ? array() : $data['permissions'];
        $project_id = (int) (empty($data['project_id']) ? 0: $data['project_id']);
        // remove user
        unset ($data['permissions'], $data['project_id']);
        if ($project_id > 0) {
            try {
                $in_project = $this->addToProject($id, $project_id);
            } catch (Exception $e) {
                $message = $e->getMessage();
                $in_project = $message === 'User is already on project';
            }
            if ($in_project) {
                if ($permissions) {
                    $permission = \TeamWorkPm::factory('permission');
                    $permissions['id'] = $id;
                    $permissions['project_id'] = $project_id;
                    if ($permission->update($permissions)) {
                        if (!$data) {
                            return true;
                        }
                    } else {
                        return false;
                    }
                } elseif (!$data) {
                    return true;
                }
            }
        }

        return $this->rest->put("$this->_action/$id", $data);
    }

    /**
     * Remove a user from a project
     * DELETE /projects/{id}/people/{id}.xml
     * Removes a user from a project.
     * Response
     * Returns HTTP status code 200 on success
     *
     * @param int $people_id
     * @param int $project_id
     *
     * @return bool
     */
    public function removeFromProject($people_id, $project_id)
    {
        return $this->rest->delete("projects/$project_id/people/$people_id");
    }
}