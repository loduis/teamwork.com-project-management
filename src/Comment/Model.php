<?php namespace TeamWorkPm\Comment;

abstract class Model extends \TeamWorkPm\Model
{
    protected function init()
    {
        $this->parent = 'comment';
        $this->action = $this->parent . 's';
        $this->fields = [
            'body'                     => true,
            'notify'                   => [
                'required'=>false,
                'attributes'=>[
                    'type'=>'array'
                ]
            ],
            'isprivate'                => false,
            'author_id'                => false,
            'pending_file_attachments' => false
        ];
    }

    /**
     * Creating a Comment
     *
     * POST /#{resource}/#{resource_id}/comments.xml
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
        return $this->rest->post(
            "$this->resource/$resource_id/$this->action",
            $data
        );
    }

    /**
     *
     * @param int $resource_id
     * @param int $page_size
     * @param int $page
     *
     * @return \TeamWorkPm\Response\Model
     */
    public function getRecent($resource_id, $page_size = 20, $page = 1)
    {
        $resource_id = (int) $resource_id;
        if ($resource_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param resource_id');
        }

        $page_size = abs((int) $page_size);
        $page      = abs((int) $page);

        $params = [
            'page' => $page,
            'pageSize'=> $page_size
        ];

        return $this->rest->get(
            "$this->resource/$resource_id/$this->action",
            $params
        );
    }
}