<?php

namespace TeamWorkPm;

class Message extends Model
{
    protected function init()
    {
        $this->fields = [
            'title' => true,
            'category_id' => [
                'required' => true,
                'type' => 'integer'
            ],
            'notify' => [
                'type' => 'array',
                'element' => 'person',
            ],
            'private' => [
                'type' => 'boolean'
            ],
            'body' => true,
            'attachments' => false,
            'pending_file_attachments' => false,
        ];
        $this->parent = 'post';
        $this->action = 'posts';
    }

    /**
     * Retrieve Multiple Messages
     *
     * GET /projects/#{project_id}/posts.xml
     * For the project ID supplied, will return the 25 most recent messages
     *
     * Get archived messages
     *
     * GET /projects/#{project_id}/posts/archive.xml
     *
     * Rather than the full message, this returns a summary record for each message in the specified project.
     *
     * @param $project_id
     * @param bool $archive
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByProject($project_id, $archive = false)
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $action = "projects/$project_id/$this->action";
        if ($archive) {
            $action .= '/archive';
        }
        return $this->fetch($action);
    }

    /**
     * Retrieve Messages by Category
     *
     * GET /projects/#{project_id}/cat/#{category_id}/posts.xml
     *
     * As before, will return you the most recent 25 messages, this time limited by project and category.
     *
     * Get archived messages by category
     *
     * GET /projects/#{project_id}/cat/#{category_id}/posts/archive.xml
     *
     * As above, but returns only the posts in the given category
     *
     * @param int $project_id
     * @param int $category_id
     * @param bool $archive
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByProjectAndCategory($project_id, $category_id, $archive = false)
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $category_id = (int)$category_id;
        if ($category_id <= 0) {
            throw new Exception('Invalid param category_id');
        }
        $action = "projects/$project_id/cat/$category_id/$this->action";
        if ($archive) {
            $action .= '/archive';
        }
        return $this->fetch($action);
    }

    /**
     * Create a message
     *
     * POST /projects/#{project_id}/posts.xml
     *
     * This will create a new message.
     * Also, you have the option of sending a notification to a list of people you specify.
     *
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function create(array $data)
    {
        $project_id = empty($data['project_id']) ? 0 : (int)$data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        if (!empty($data['files'])) {
            $file = Factory::build('file');
            $data['pending_file_attachments'] = $file->upload($data['files']);
            unset($data['files']);
        }
        return $this->post("projects/$project_id/$this->action", $data);
    }
}
