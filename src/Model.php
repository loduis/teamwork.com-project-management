<?php

namespace TeamWorkPm;

abstract class Model extends Rest\Resource
{
    /**
     * @param int $id
     * @param object|array|null $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get(int $id, object|array|null $params = null)
    {
        $this->validateId($id);

        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * @param array|object $data
     * @return int
     */
    public function insert(array|object $data): int
    {
        /**
         * @var int
         */
        return $this->rest->post((string) $this->action, $data);
    }

    /**
     * @param array|object $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array|object $data): bool
    {
        $data = arr_obj($data);
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->rest->put("$this->action/$id", $data) === true;
    }

    /**
     * @param array|object $data
     *
     * @return bool|int
     * @throws Exception
     */
    final public function save(array|object $data): int|bool
    {
        $data = arr_obj($data);

        return $data->offsetExists('id')
            ? $this->update($data)
            : $this->insert($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
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
