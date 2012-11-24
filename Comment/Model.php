<?php
namespace TeamWorkPm\Comment;

abstract class Model extends \TeamWorkPm\Model
{
    protected function _init()
    {
        $this->_parent = 'comment';
        $this->_action = $this->_parent . 's';
        $this->_fields = array(
            'body'                     => true,
            'notify'                   => false,
            'isprivate'                => false,
            'author_id'                => false,
            'pending_file_attachments' => false
        );
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
        $resource_id = (int) $data['resource_id'];
        if ($resource_id <= 0) {
            throw new Exception('Require field resource_id');
        }
        return $this->rest->post("$this->_resource/$resource_id/$this->_action", $data);
    }

    /**
     *
     * @param int $resource_id
     * @param array $params [page, pageSize] this is only posible values
     * @return TeamWorkPm\Response\Model
     */
    public function getRecent($resource_id, array $params = array())
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        foreach ($params as $name=>$value) {
            if (!in_array(strtolower($name), array('page', 'pagesize'))) {
                unset ($params[$name]);
            }
        }
        return $this->rest->get("$this->_resource/$resource_id/$this->_action", $params);
    }
}