<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Rest\Response\Model as Response;

trait BaseTrait
{
    /**
     *
     * @param object|array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->fetch("$this->action", $params);
    }

    /**
     * @param int $id
     * @param object|array|null $params
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $id, object|array|null $params = null)
    {
        $this->validates(['id' => $id]);

        return $this->fetch("$this->action/$id", $params);
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
        return $this->post((string) $this->action, $data);
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
        return $this->put("$this->action/$id", $data);
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

        return $this->del("$this->action/$id");
    }
}