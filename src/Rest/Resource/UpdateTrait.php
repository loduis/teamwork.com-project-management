<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

trait UpdateTrait
{
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
}