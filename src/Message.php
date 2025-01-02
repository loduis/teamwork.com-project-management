<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\ArchiveTrait;
use TeamWorkPm\Rest\Resource\MarkAsReadTrait;
use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\Project\CreateTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/messages/get-posts-json
 */
class Message extends Model
{
    protected ?string $parent = 'post';

    protected ?string $action = 'posts';

    protected string|array $fields = 'messages';

    use CreateTrait, MarkAsReadTrait;

    /**
     * Get all Resource on a given Project
     *
     * @param int $id
     * @param boolean $archived
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, bool $archived = false): Response
    {
        $action = "projects/$id/$this->action";
        if ($archived) {
            $action .= '/archive';
        }
        return $this->fetch($action);
    }

    /**
     * Retrieve Messages by Category | Get Archived Messages by Category
     *
     * @param integer $projectId
     * @param integer $categoryId
     * @param boolean $archived
     * @return Response
     */
    public function getByProjectAndCategory(int $projectId, int $categoryId, bool $archived = false): Response
    {
        $action = "projects/$projectId/cat/$categoryId/$this->action";
        if ($archived) {
            $action .= '/archive';
        }
        return $this->fetch($action);
    }

        /**
     * Mark a Resource as archive
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function archive(int $id): bool
    {
        return $this->put("messages/$id/archive");
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
        return $this->put("messages/$id/unarchive");
    }

    /**
     * Mark a Resource as Read
     *
     * @param integer $id
     * @return boolean
     */
    public function markAsRead(int $id): bool
    {
        return $this->put("messages/$id/markread");
    }
}

