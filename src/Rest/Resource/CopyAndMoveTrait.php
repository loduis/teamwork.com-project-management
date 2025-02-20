<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

trait CopyAndMoveTrait
{
    /**
     * Copy a Resource to another Project
     *
     * @param integer $id
     * @param integer $projectId
     * @return int
     */
    public function copy(int $id, int $projectId): int
    {
        return $this
            ->notUseFields()
            ->put(
            "$this->action/$id/copy", compact('projectId')
        );
    }

    /**
     * Move a resource to another Project
     *
     * @param integer $id
     * @param integer $projectId
     * @return boolean
     */
    public function move(int $id, int $projectId): bool
    {
        return $this
            ->notUseFields()
            ->put("$this->action/$id/move", compact('projectId')
        );
    }
}