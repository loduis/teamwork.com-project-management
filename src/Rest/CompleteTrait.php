<?php

namespace TeamWorkPm\Rest;

trait CompleteTrait
{
    /**
     * Mark a Task complete
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function complete(int $id): bool
    {
        return $this->put("$this->action/$id/complete");
    }

    /**
     * Mark an Item Uncomplete
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function unComplete(int $id): bool
    {
        return $this->put("$this->action/$id/uncomplete");
    }
}