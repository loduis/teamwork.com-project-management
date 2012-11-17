<?php
namespace TeamWorkPm\Comment;

class File extends Model
{
    protected  $_resource = 'files';

    /**
     * Creating a Comment
     *
     * POST /#{resource}/#{resource_id}/comments
     *
     *  Creates a new comment, associated with the particular resource.
     * When named in the URL, it can be either posts, todo_items or milestones.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $resource_id = (int) $data['resource_id'];
        if ($resource_id <= 0) {
            throw new Exception('Require field resource_id');
        }
        return $this->_post("fileversions/$resource_id/$this->_action", $data);
    }
}