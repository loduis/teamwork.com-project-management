<?php

declare(strict_types = 1);

namespace TeamWorkPm\Message;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource\Model;

class Reply extends Model
{
    public function init()
    {
        $this->fields = [
            'body' => true,
            'notify' => [
                'type' => 'array',
                'element' => 'person',
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
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByMessage($message_id, array $params = [])
    {
        $message_id = (int)$message_id;
        if ($message_id <= 0) {
            throw new Exception('Invalid param message_id');
        }
        $validate = ['page', 'pagesize'];
        foreach ($params as $name => $value) {
            if (!in_array(strtolower($name), $validate)) {
                unset($params[$name]);
            }
        }
        return $this->fetch("messages/$message_id/replies", $params);
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
     *
     * @return int
     * @throws Exception
     */
    public function create(array $data)
    {
        $message_id = empty($data['message_id']) ? 0 : (int)$data['message_id'];
        if ($message_id <= 0) {
            throw new Exception('Required field message_id');
        }
        return $this->post("messages/$message_id/messageReplies", $data);
    }
}
