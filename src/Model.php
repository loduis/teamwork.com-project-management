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
        $this->validates(['id' => $id]);

        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * @param array|object $data
     * @return int
     */
    public function create(array|object $data): int
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
        $id = (int) $data->pull('id');
        if ($id <= 0) {
            if ($this->parent && $data->offsetExists($this->parent)) {
                $entry = $data[$this->parent];
                $id = (int) $entry->pull('id');
            }
            $this->validates([
                'id' => $id
            ], true);
        }

        /** @var bool */
        return $this->rest->put("$this->action/$id", $data);
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

        return (
            $data->offsetExists('id') || (
                $this->parent &&
                $data->offsetExists($this->parent) &&
                ($entry = $data[$this->parent]) &&
                $entry->offsetExists('id')
            )
        )
            ? $this->update($data)
            : $this->create($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $this->validates(['id' => $id]);

        return $this->rest->delete("$this->action/$id");
    }
}
