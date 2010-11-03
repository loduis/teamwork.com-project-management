<?php

class TeamWorkPm_People extends TeamWorkPm_Model
{

    /**
     *
     * @var array
     */
    protected $_fields = array(
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
        'im_service'=>false,
        'dateFormat'=>array('required'=>false, 'validate'=>array(
            'dd.mm.yyyy','dd/mm/yyyy','mm.dd.yyyy','mm/dd/yyyy',
            'yyyy-mm-dd','yyyy.mm.dd')
        ),
        'sendWelcomeEmail'=>false,
        'receiveDailyReports'=>false,
        'autoGiveProjectAccess'=>false,
        'openID'=>false,
        'notes'=>false,
        'userLanguage'=>array('required'=>false, 'validate'=>array('EN', 'FR', 'AR', 'BG', 'ZH', 'HR', 'CS', 'DA', 'NL', 'FI', 'DE', 'EL', 'HU', 'ID', 'IT', 'JA', 'KO', 'NO', 'PL
        ', 'PT', 'RO', 'RU', 'ES', 'SV')),
        'administrator'=>false,
        'canAddProjects'=>false

    );

    /**
     * @var People
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return People
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }

    protected function _init()
    {
        $this->_parent = 'person';
        $this->_action = 'people';
    }
}