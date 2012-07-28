<?php

abstract class TeamWorkPm_Model extends TeamWorkPm_Rest_Model
{

    /*------------------------------
            PUBLIC METHOD
     ------------------------------*/

    public function get($id, $params = array())
    {
        $id = (int) $id;
        return $this->_get("$this->_action/$id", $params);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->_post($this->_action, $data);
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
            throw new TeamWorkPm_Exception('Require field id');
        }
        return $this->_put("$this->_action/$id", $data);
    }
    /**
     *
     * @param array $data
     * @return bool
     */
    final public function save(array $data)
    {
        return !empty($data['id']) ?
            $this->update($data) :
            $this->insert($data);
    }
    /**
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
    {
        $id = (int) $id;
        if (empty($id)) {
            throw new TeamWorkPm_Exception('Require field id');
        }
        return $this->_delete("$this->_action/$id");
    }
}