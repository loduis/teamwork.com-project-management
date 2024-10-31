<?php

namespace TeamWorkPm\Comment;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/comments
 */
abstract class Model extends \TeamWorkPm\Model
{
    protected string $resource;

    protected ?string $parent = 'comment';

    protected ?string $action = 'comments';

    protected static string|array $fields = 'resource_comments';

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
        $resourceId = (int) $data->pull('resource_id');
        $this->validates([
            'resource_id' => $resourceId
        ]);
        $files = $data->pull('files');
        if ($files !== null) {
            $data['pending_file_attachments'] = Factory::file()
                ->upload($files);
        }

        $resource = $this->resource;

        if ($resource === 'files') {
            $resource = 'fileversions';
        }

        return $this->rest->post(
            "$resource/$resourceId/$this->action",
            $data
        );
    }

    /**
     * Retrieving Recent Comments
     *
     * @param int $resourceId
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */
    public function getRecent(int $resourceId, array|object $params = []): Response
    {
        return $this->rest->get(
            "$this->resource/$resourceId/$this->action",
            $params
        );
    }
}
