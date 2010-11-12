<?php

class TeamWorkPm_People extends TeamWorkPm_Model
{

    protected function _init()
    {
        $this->_parent = 'person';
        $this->_action = 'people';
        $this->_fields = array(
            'first_name' => true,
            'last_name'  => true,
            'email'=>true,
            'user_name'  => true,
            'password'   => true,

            'company_id' => false,
            'title' => false,
            'phone_number_mobile'=>false,
            'phone_number_office'=>false,
            'phone_number_office_ext=>false',
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
            'sendWelcomeEmail'=>false,
            'receiveDailyReports'=>false,
            'autoGiveProjectAccess'=>false,
            'openID'=>false,
            'notes'=>false,
            'userLanguage'=>array('required'=>false, 'validate'=>array(
                'EN', 'FR', 'AR', 'BG', 'ZH', 'HR', 'CS', 'DA', 'NL',
                'FI', 'DE', 'EL', 'HU', 'ID', 'IT', 'JA', 'KO', 'NO', 'PL',
                'PT', 'RO', 'RU', 'ES', 'SV')),
            'administrator'=>false,
            'canAddProjects'=>false

        );
    }
}