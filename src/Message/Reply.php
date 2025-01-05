<?php

declare(strict_types = 1);

namespace TeamWorkPm\Message;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\GetTrait;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\MarkAsReadTrait;
use TeamWorkPm\Rest\Resource\SaveTrait;
use TeamWorkPm\Rest\Resource\UpdateTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/message-replies/get-message-replies-id-json
 */
class Reply extends Resource
{
    use MarkAsReadTrait, UpdateTrait, SaveTrait, DestroyTrait, GetTrait;

    protected ?string $parent = 'messagereply';

    protected ?string $action = 'messageReplies';

    protected string|array $fields = "messages.replies";

    /**
     * Retrieve Replies to a Message
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByMessage(int $id, array|object $params = []): Response
    {
        return $this->fetch("messages/$id/replies", $params);
    }

    /**
     * Create a Message Reply
     *
     * @param array|object $data
     * @return int
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);
        $messageId = $data->pull('message_id');

        $this->validates([
            'message_id' => $messageId
        ], true);

        return $this->post("messages/$messageId/$this->action", $data);
    }
}
