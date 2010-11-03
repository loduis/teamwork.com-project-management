<?php

class TeamWorkPm_Company extends TeamWorkPm_Get
{
    /**
     * @var Company
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return Company
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key);
        }

        return self::$_instance;
    }

    public function get($id = null)
    {
        $action = 'companies';
        if (null !== $id) {
            $action .= '/' . $id;
        }
        return $this->_get($action);
    }

    public function getByProjectId($id)
    {
        return $this->get("projects/$id/companies");
    }

    public function  insert(array $data)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }

    public function  update(array $data)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }

    public function  delete($id)
    {
        throw new TeamWorkPm_Exception('Invalid Action');
    }
}