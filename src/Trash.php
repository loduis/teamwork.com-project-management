<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Response\Model as Response;
/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/trashcan/get-trashcan-projects-id-json
 */
class Trash extends Resource
{
    protected ?string $parent = '';

    protected ?string $action = 'trashcan';

    protected string|array $fields = [];

    protected const RESOURCES = [
        'projects',
        'tasks',
        'milestones',
        'tasklists',
        'messages',
        'messagereplies',
        'notebooks',
        'files',
        'fileversions',
        'timelogs',
        'people',
        'links',
        'comments',
    ];

    /**
     *
     * @param int $projectId
     * @return Response
     * @throws Exception
     */
    public function all(int $projectId): Response
    {
        return $this->fetch("$this->action/projects/$projectId");
    }

    /**
     * Restore Resource Items from the Trashcan
     *
     * @param string $resource
     * @param integer $id
     * @return boolean
     */
    public function restore(string $resource, int $id): bool
    {
        if (!in_array($resource, static::RESOURCES, true)) {
            throw new Exception('Invalid resource name: ' . $resource);
        }

        return $this->notUseFields()
            ->put("$this->action/$resource/$id/restore");
    }
}
