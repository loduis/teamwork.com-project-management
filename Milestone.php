<?php

class TeamWorkPm_Milestone extends TeamWorkPm_Model
{
    /**
     *
     * @var array
     */
    protected $_fields = array(
        'title'=>true,
        'deadline'=>true,//format YYYYMMDD
        'notify'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
        'reminder'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
        'isprivate'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
        'responsible_party_ids'=>true
    );

    /**
     * @var Milestone
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Milestone
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }

    public function markAsComplete($id)
    {
        return $this->_put("{$this->_action}/$id/complete");
    }

    public function markAsUnComplete($id)
    {
        return $this->_put("{$this->_action}$id/uncomplete");
    }
    /**
     *
     * @param string $filter  [all|completed|incomplete|late|upcoming]
     */
    public function getAll($filter = 'all')
    {
        return $this->_get($this->_action, array('find'=>$filter));
    }
}