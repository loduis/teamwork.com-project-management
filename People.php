<?php

class TeamWorkPm_People extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_parent = 'person';
        $this->_action = 'people';
        $this->_fields = array(
            'first_name' => true,
            'last_name' => true,
            'email' => true,
            'user_name' => true,
            'password' => true,
            'email_address'=>false,
            'administrator'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),

            'company_id' => false,
            'title' => false,
            'phone_number_mobile'=>false,
            'phone_number_office'=>false,
            'phone_number_office_ext'=>false,
            'phone_number_fax'=>false,
            'phone_number_home'=>false,
            'im_handle'=>false,
            'im_service'=>array('required'=>false, 'validate'=>array(
                'GTalk', 'AOL', 'ICQ', 'MSN', 'Jabber', 'Yahoo', 'Skype', 'Twitter'
            )),
            'dateFormat'=>array('required'=>false, 'validate'=>array(
                'dd.mm.yyyy','dd/mm/yyyy','mm.dd.yyyy','mm/dd/yyyy',
                'yyyy-mm-dd','yyyy.mm.dd')
            ),
            'sendWelcomeEmail'=>array('required'=>false, 'validate'=>array('yes', 'no')),
            'receiveDailyReports'=>array('required'=>false, 'validate'=>array('yes', 'no')),
            'autoGiveProjectAccess'=>array('required'=>false, 'validate'=>array('yes', 'no')),
            'openID'=>false,
            'notes'=>array('required'=>false, 'validate'=>array('yes', 'no')),
            'userLanguage'=>array('required'=>false, 'validate'=>array(
                'EN', 'FR', 'AR', 'BG', 'ZH', 'HR', 'CS', 'DA', 'NL',
                'FI', 'DE', 'EL', 'HU', 'ID', 'IT', 'JA', 'KO', 'NO', 'PL',
                'PT', 'RO', 'RU', 'ES', 'SV')),
            'administrator'=>false,
            'canAddProjects'=>array('required'=>false, 'validate'=>array('yes', 'no'))
        );
    }

    public function insert(array $data)
    {
        return $this->_post("$this->_action", $data);
    }
}