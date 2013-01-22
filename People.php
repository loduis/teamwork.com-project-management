<?php
namespace TeamWorkPm;

class People extends Model
{

    protected function _init()
    {
        $this->_fields = array(
            'first_name' => true,
            'last_name' => true,
            'email_address'=>true,
            'user_name' => true,
            'password' => false,
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
                'type'=>'boolean'
            ),
            'receive_daily_reports'=>array(
                'required'=>false,
                'type'=>'boolean'
            ),
            'welcome_email_message'=>false,
            'auto_give_project_access'=>array(
                'required'=>false,
                'type'=>'boolean'
            ),
            'open_id'=>false,
            'notes'=>array(
                'required'=>false,
                'type'=>'boolean'
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
                'type'=>'boolean'
            )
        );
        $this->_parent = 'person';
        $this->_action = 'people';
    }

    public function get($id, $project_id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $project_id = (int) $project_id;
        $action = "$this->_action/$id";
        if ($project_id) {
            $action = "projects/$project_id/$action";
        }
        return $this->rest->get($action);
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
     * Add a new user
     * POST /people
     * Creates a new user account
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        // validate email address
        if (!empty($data['email_address']) &&
                !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                'Invalid value for field email_address'
            );
        }
        $project_id = empty($data['project_id']) ? 0 : $data['project_id'];
        $permissions = empty($data['permissions']) ? null :
                                                (array) $data['permissions'];
        unset($data['project_id'], $data['permissions']);
        $id = parent::insert($data);
        // add permission to project
        if ($project_id) {
            $permission = \TeamWorkPm::factory('project/people');
            $permission->add($project_id, $id);
            if ($permissions) {
                $permissions['person_id']  = $id;
                $permissions['project_id'] = $project_id;
                $permission->update($permissions);
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
        // validate email address
        if (!empty($data['email_address']) &&
                !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                'Invalid value for field email_address'
            );
        }
        $project_id = empty($data['project_id']) ? 0 : $data['project_id'];
        $permissions = empty($data['permissions']) ? null :
                                                (array) $data['permissions'];
        unset($data['project_id'], $data['permissions']);
        $save = false;
        if (!empty($data)) {
            $save = parent::update($data);
        }
        // add permission to project
        if ($project_id) {
            $permission = \TeamWorkPm::factory('project/people');
            try {
                $add = $permission->add($project_id, $data['id']);
            } catch(Exception $e) {
                $add = $e->getMessage() == 'User is already on project';
            }
            $save = $save && $add;
            if ($add && $permissions) {
                $permissions['person_id']  = $data['id'];
                $permissions['project_id'] = $project_id;
                $save = $permission->update($permissions);
            }
        }
        return $save;
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete($id, $project_id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $project_id = (int) $project_id;
        $action = "$this->_action/$id";
        if ($project_id) {
            $action = "projects/$project_id/$action";
        }
        return $this->rest->delete($action);
    }
}
