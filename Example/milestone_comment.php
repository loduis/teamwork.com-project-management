<?php

require_once './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('insert', 'get_recent'))) {
        return get_first_incomplete_milestone();
    } else {
        return get_first_milestone_comment();
    }
});

function test_insert($milestone_id)
{
    $comment = TeamWorkPm::factory('comment/milestone');
    try {
       $data = array(
          'resource_id'=> $milestone_id,
          'body'=> 'Comment, bla, bla, comment'
        );
        $id = $comment->insert($data);
        echo 'Insert milestone comment: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $comment = TeamWorkPm::factory('comment/milestone');
    try {
        $data = array(
            'id'=>$id,
            'body'=> 'Update this body'
        );
        if ($comment->update($data)) {
            echo 'Update milestone comment: ', $id, "\n";
        } else {
            echo 'Can not Update milestone comment: ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_recent($milestone_id)
{
    $comment = TeamWorkPm::factory('comment/milestone');
    try {
        $comments = $comment->getRecent($milestone_id);
        foreach ($comments as $c) {
            print_r($c);
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $comment = TeamWorkPm::factory('comment/milestone');
    try {
        $comment = $comment->get($id);
        print_r($comment);
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_delete($id)
{
    $comment = TeamWorkPm::factory('comment/milestone');
    try {
        if ($comment->delete($id)) {
            echo 'Delete milestone comment ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function get_first_milestone_comment()
{
    $milestone_id = get_first_incomplete_milestone();
    $comment = TeamWorkPm::factory('comment/milestone');
    $comments = $comment->getRecent($milestone_id);
    foreach ($comments as $c) {
        return $c->id;
    }
}