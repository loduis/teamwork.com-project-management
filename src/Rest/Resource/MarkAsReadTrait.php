<?php

namespace TeamWorkPm\Rest\Resource;

trait MarkAsReadTrait
{
    /**
     * Mark a Resource as Read
     *
     * @param integer $id
     * @return boolean
     */
    public function markAsRead(int $id): bool
    {
        return $this->put("$this->action/$id/markread");
    }
}