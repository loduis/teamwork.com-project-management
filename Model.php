<?php
namespace TeamWorkPm;

abstract class Model extends Rest\Model
{

    /*------------------------------
            PUBLIC METHOD
     ------------------------------*/

    public function get($id, $params = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->get("$this->_action/$id", $params);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->rest->post($this->_action, $data);
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $id = isset($data['id']) ? (int) $data['id'] : 0;
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->rest->put("$this->_action/$id", $data);
    }
    /**
     *
     * @param array $data
     * @return bool
     */
    final public function save(array $data)
    {
        return isset($data['id']) ?
            $this->update($data):
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
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->delete("$this->_action/$id");
    }
}