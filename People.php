<?php

class TeamWorkPm_People extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_fields = array(
            'first_name' => TRUE,
            'last_name' => TRUE,
            'email_address'=>FALSE,
            'user_name' => TRUE,
            'password' => TRUE,
            'company_id' => FALSE,
            'title' => FALSE,
            'phone_number_mobile'=>FALSE,
            'phone_number_office'=>FALSE,
            'phone_number_office_ext'=>FALSE,
            'phone_number_fax'=>FALSE,
            'phone_number_home'=>FALSE,
            'im_handle'=>FALSE,
            'im_service'=>array('required'=>FALSE, 'validate'=>array(
                'GTalk', 'AOL', 'ICQ', 'MSN', 'Jabber', 'Yahoo', 'Skype', 'Twitter'
            )),
            'date_format'=>array('required'=>FALSE, 'validate'=>array(
                'dd.mm.yyyy','dd/mm/yyyy','mm.dd.yyyy','mm/dd/yyyy',
                'yyyy-mm-dd','yyyy.mm.dd')
            ),
            'send_welcome_email'=>array('required'=>FALSE, 'validate'=>array('yes', 'no')),
            'receive_daily_reports'=>array('required'=>FALSE, 'validate'=>array('yes', 'no')),
            'welcome_email_message'=>FALSE,
            'auto_give_project_access'=>array('required'=>FALSE, 'validate'=>array('yes', 'no')),
            'open_id'=>FALSE,
            'notes'=>array('required'=>FALSE, 'validate'=>array('yes', 'no')),
            'user_language'=>array('required'=>FALSE, 'validate'=>array(
                'EN', 'FR', 'AR', 'BG', 'ZH', 'HR', 'CS', 'DA', 'NL',
                'FI', 'DE', 'EL', 'HU', 'ID', 'IT', 'JA', 'KO', 'NO', 'PL',
                'PT', 'RO', 'RU', 'ES', 'SV')),
            'administrator'=>FALSE,
            'can_add_projects'=>array('required'=>FALSE, 'validate'=>array('yes', 'no'))
        );
        $this->_parent = 'person';
        $this->_action = 'people';
    }

    /**
     * Get people
     * GET /people.xml
     * All people visible to the user will be returned, including the user themselves
     *
     * @return TeamWorkPm_Response_Model
     */
    public function getAll()
    {
        return $this->_get($this->_action);
    }
    /**
     * Get all People (within a Project)
     * GET /projects/#{project_id}/people
     * Retrieves all of the people in a given project
     *
     * @param type $id
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        return $this->_get("projects/$id/$this->_action");
    }

    /**
     * Get People (within a Company)
     * GET /companies/#{company_id}/people
     * Retreives the details for all the people from the submitted company
     * (excluding those you don't have permission to see)
     *
     * @param int $id
     * @return TeamWorkPm_Response_Model
     */
    public function getByCompany($id)
    {
        $id = (int) $id;
        return $this->_get("companies/$id/$this->_action");
    }
}