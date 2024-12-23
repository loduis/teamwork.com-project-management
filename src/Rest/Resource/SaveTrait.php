<?php

namespace TeamWorkPm\Rest\Resource;

trait SaveTrait
{
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
}