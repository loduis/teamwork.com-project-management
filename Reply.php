<?php

class TeamWorkPm_Reply extends TeamWorkPm_Model
{
    /**
     *
     * @var array
     */
    protected $_fields = array(
        'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
        'body'=>true,
        'notify'=>array('required'=>false, 'attributes'=>array('type'=>'array'), 'element'=>'person'),
    );

    /**
     * @var MessageReply
     */
    private static $_instance;

    /**
     *
     * @param string $company
     * @param string $key
     * @return MessageReply
     */
    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }

    public function _init()
    {
        $this->_parent = 'messagereply';
        $this->_action = 'messageReplies';
    }


    public function getByMessageId($id, $params = array())
    {
       return $this->_get("messages/$id/replies", $params);
    }

    public function insert(array $data)
    {
        $message_id = $data['message_id'];
        if (empty($message_id)) {
            throw new TeamWorkPm_Exception('Require field message id');
        }
        return $this->_post("messages/$message_id/messageReplies", $data);
    }
}