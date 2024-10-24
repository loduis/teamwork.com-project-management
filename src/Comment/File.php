<?php

namespace TeamWorkPm\Comment;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/comments
 */
class File extends Model
{
    protected $resource = 'files';

    /**
     * Creating a Comment
     *
     * POST /#{resource}/#{resource_id}/comments
     *
     *  Creates a new comment, associated with the particular resource.
     * When named in the URL, it can be either posts, todo_items or milestones.
     *
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function insert(array $data)
    {
        $resource_id = empty($data['resource_id']) ? 0 : (int)$data['resource_id'];
        if ($resource_id <= 0) {
            throw new Exception('Required field resource_id');
        }
        if (!empty($data['files'])) {
            $file = Factory::build('file');
            $data['pending_file_attachments'] = $file->upload($data['files']);
            unset($data['files']);
        }
        return $this->rest->post("fileversions/$resource_id/$this->action", $data);
    }
}
