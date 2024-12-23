<?php

namespace TeamWorkPm\Rest\Resource;

trait DestroyTrait
{
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