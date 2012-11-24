<?php
namespace TeamWorkPm\Message;

class Reply extends \TeamWorkPm\Model
{
    public function _init()
    {
        $this->_fields = array(
            'body'=>true,
            'notify'=>array(
                'required'=>false,
                'attributes'=>array('type'=>'array'),
                'element'=>'person'
            ),
        );
        $this->_parent = 'messagereply';
        $this->_action = 'messageReplies';
    }

    /**
     * Retrieve Replies to a Message
     *
     * GET /messages/#{id}/replies.xml
     *
     * Uses the given messsage ID to retrieve a all replies to a message specified in the url.
     * By default 20 records are returned at a time. You can pass "page" and "pageSize" to change this:
     * eg. GET /messages/54/replies.xml?page=2&pageSize=50.
     *
     * The following headers are returned:
     * "X-Records" - The total number of replies
     * "X-Pages" - The total number of pages
     * "X-Page" - The page you requested
     *
     * @param <type> $id
     * @param <type> $params
     * @return TeamWorkPm\Response\Model
     */
    public function getByMessage($id, array $params = array())
    {
        foreach ($params as $name=>$value) {
            if (!in_array(strtolower($name), array('page', 'pagesize'))) {
                unset ($params[$name]);
            }
        }
        return $this->rest->get("messages/$id/replies", $params);
    }

    /**
     * Create a Message Reply
     *
     * POST /messages/#{message_id}/messageReplies.xml
     *
     * This will create a new message.
     * Also, you have the option of sending a notification to a list of people you select.people.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $message_id = $data['message_id'];
        if (empty($message_id)) {
            throw new \TeamWorkPm\Exception('Require field message_id');
        }
        return $this->rest->post("messages/$message_id/messageReplies", $data);
    }
}