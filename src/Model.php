<?php

namespace TeamWorkPm;

abstract class Model extends Rest\Resource
{
    /**
     * @param int $id
     * @param string $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get(int $id, $params = null)
    {
        $this->validateId($id);

        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->rest->post($this->action, $data);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array $data)
    {
        $id = empty($data['id']) ? 0 : (int)$data['id'];
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->rest->put("$this->action/$id", $data);
    }

    /**
     * @param array $data
     *
     * @return [bool|int]
     * @throws Exception
     */
    final public function save(array $data)
    {
        return array_key_exists('id', $data)
            ? $this->update($data)
            : $this->insert($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function delete(int $id)
    {
        $this->validateId($id);

        return $this->rest->delete("$this->action/$id");
    }

    protected function validateId(int $id): void
    {
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
    }
}
