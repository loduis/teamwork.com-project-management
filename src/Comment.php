<?php

namespace TeamWorkPm;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/comments
 */
class Comment extends Model
{
    protected ?string $resource = null;

    protected ?string $parent = 'comment';

    protected ?string $action = 'comments';

    protected string|array $fields = 'resource_comments';

    /**
     * Create a comment related to a task/message/notebook etc.
     *
     * @param array|object $data
     *
     * @return int
     * @throws Exception
     */
    public function create(array|object $data): int
    {

        $data = arr_obj($data);
        $this->validateResource(
            $resource = $this->resource ?? $data->pull('resource_name')
        );
        $resourceId = (int) $data->pull('resource_id');
        $this->validates([
            'resource_id' => $resourceId
        ], true);
        $files = $data->pull('files');
        if ($files !== null) {
            $data['pending_file_attachments'] = Factory::file()
                ->upload($files);
        }

        return $this->post(
            "$resource/$resourceId/$this->action",
            $data
        );
    }

    /**
     * Mark a Comment as Read
     *
     * @param integer $id
     * @return boolean
     */
    public function markAsRead(int $id): bool
    {
        return $this->put("$this->action/$id/markread");
    }

    /**
     * Retrieving Comments across all types
     *
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function all(array|object $params = []): Response
    {
        $params = arr_obj($params);

        if (!$params->offsetExists('objectType') && $this->resource !== null) {
            $type =  substr($this->resource, 0, -1);
            $params['objectType'] = str_replace('version', '', $type);
        }

        return parent::all($params);
    }

    /**
     * Retrieving Recent Comments
     *
     * @param int $resourceId
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getRecent(int $resourceId, array|object $params = []): Response
    {
        $params = arr_obj($params);

        $this->validateResource(
            $resource = $this->resource ?? $params->pull('resource_name')
        );

        return $this->fetch(
            "$resource/$resourceId/$this->action",
            $params
        );
    }

    protected function validateResource(?string $resource): void
    {
        if ($resource === null || !in_array($resource, [
            'fileversions',
            'tasks',
            'notebooks',
            'links'
        ])) {
            throw new Exception('Required resource_name');
        }
    }
}
