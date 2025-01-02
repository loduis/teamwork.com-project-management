<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

trait ArchiveTrait
{
    /**
     * Mark a Resource as archive
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function archive(int $id): bool
    {
        return $this->put("$this->action/$id/archive");
    }

    /**
     * Mark a Resource as unarchive
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function unArchive(int $id): bool
    {
        return $this->put("$this->action/$id/unarchive");
    }
}