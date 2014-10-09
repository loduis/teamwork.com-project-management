<?php namespace TeamWorkPm\Comment;

class File extends Model
{
    protected  $resource = 'files';

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
        $resource_id = empty($data['resource_id']) ? 0 :
                                (int) $data['resource_id'];
        if ($resource_id <= 0) {
            throw new \TeamWorkPm\Exception('Required field resource_id');
        }
        if (!empty($data['files'])) {
            $file = \TeamWorkPm\Factory::build('file');
            $data['pending_file_attachments'] = $file->upload($data['files']);
            unset($data['files']);
        }
        return $this->rest->post("fileversions/$resource_id/$this->action", $data);
    }
}