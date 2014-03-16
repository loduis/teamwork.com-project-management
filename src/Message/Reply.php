<?php namespace TeamWorkPm\Message;

class Reply extends \TeamWorkPm\Model
{
    public function init()
    {
        $this->fields = [
            'body'=>true,
            'notify'=>[
                'required'=>false,
                'attributes'=>['type'=>'array'],
                'element'=>'person'
            ],
        ];
        $this->parent = 'messagereply';
        $this->action = 'messageReplies';
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
    public function getByMessage($message_id, array $params = [])
    {
        $message_id = (int) $message_id;
        if ($message_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param message_id');
        }
        $validate = ['page', 'pagesize'];
        foreach ($params as $name=>$value) {
            if (!in_array(strtolower($name), $validate)) {
                unset ($params[$name]);
            }
        }
        return $this->rest->get("messages/$message_id/replies", $params);
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

        $message_id = empty($data['message_id']) ? 0 : (int) $data['message_id'];
        if ($message_id <= 0) {
            throw new \TeamWorkPm\Exception('Required field message_id');
        }
        return $this->rest->post("messages/$message_id/messageReplies", $data);
    }
}