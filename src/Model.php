<?php namespace TeamWorkPm;

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
        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->rest->post($this->action, $data);
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $id = empty($data['id']) ? 0: (int) $data['id'];
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->rest->put("$this->action/$id", $data);
    }

    /**
     *
     * @param array $data
     * @return [bool|int]
     */
    final public function save(array $data)
    {
        return array_key_exists('id', $data) ?
            $this->update($data):
            $this->insert($data);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->delete("$this->action/$id");
    }
}