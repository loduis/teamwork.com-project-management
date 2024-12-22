<?php

declare(strict_types = 1);

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Response\Model as Response;
use TeamWorkPm\Rest\Resource\GetByProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/files/post-projects-id-files-json
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/files/get-projects-id-files-json
 */
class File extends Resource
{
    use GetByProjectTrait;

    protected ?string $parent = 'file';

    protected ?string $action = 'files';

    protected string|array $fields = 'projects.files';

    /**
     * List Files on a Project
     *
     * @param integer $id
     * @return Response
     * @throws Exception
     */
    public function all(int $id): Response
    {
        return $this->getByProject($id);
    }

    /**
     * Add a File to a Project
     *
     * @param integer $id
     * @param object|array $data
     * @return int
     * @throws Exception
     */
    public function add(int $id, object|array $data): int
    {
        return $this->post("projects/$id/$this->action", $data);
    }
}