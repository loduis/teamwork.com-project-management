<?php

require_once './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('insert', 'get_by_message'))) {
        return get_first_message();
    } else {
        return get_first_reply();
    }
});

function test_insert($message_id)
{
    $reply = TeamWorkPm::factory('message/reply');
    try {
        $people_id = get_first_people();
        $notify = array(
            $people_id
        );
        $data = array(
            'message_id'=> $message_id,
            'body' => 'Bla, Bla, Bla',
            'notify' => $notify
        );
        $id = $reply->insert($data);
        echo 'INSERT MESSAGE REPLY: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $reply = TeamWorkPm::factory('message/reply');
    try {
        $data = array(
            'id'=> $id,
            'body' => 'Bla, Bla, Bla, update'
        );
        if ($reply->update($data)) {
            echo 'UPDATE MESSAGE REPLY: ', $id, "\n";
        } else {
            echo 'CAN NOT UPDATE MESSAGE REPLY: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_delete($id) {
    $category = TeamWorkPm::factory('message/reply');
    try {
        echo '------------------TEST DELETE---------------------', "\n";
        if ($category->delete($id)) {
            echo 'Delete message reply: ', $id, "\n";
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get_by_message($message_id)
{
    $reply = TeamWorkPm::factory('message/reply');
    try {
        $replies = $reply->getByMessage($message_id);
        foreach ($replies as $r) {
            print_r($r);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $reply = TeamWorkPm::factory('message/reply');
    try {
        $reply = $reply->get($id);
        print_r($reply);
    } catch (Exception $e) {
        print_r($e);
    }
}

function get_first_reply() {
    $message_id = get_first_message();
    $reply = TeamWorkPm::factory('message/reply');
    try {
        $replies = $reply->getByMessage($message_id);
        foreach ($replies as $r) {
            return $r->id;
        }
    } catch (Exception $e) {
        print_r($e);
    }
}
