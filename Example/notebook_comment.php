<?php

require_once './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('insert', 'get_recent'))) {
        return get_first_notebook();
    } else {
        return get_first_notebook_comment();
    }
});

function test_insert($notebook_id)
{
    $comment = TeamWorkPm::factory('comment/notebook');
    try {
       $data = array(
          'resource_id'=> $notebook_id,
          'body'=> 'Comment, bla, bla, comment'
        );
        $id = $comment->insert($data);
        echo 'Insert notebook comment: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $comment = TeamWorkPm::factory('comment/notebook');
    try {
        $data = array(
            'id'=>$id,
            'body'=> 'Update this body'
        );
        if ($comment->update($data)) {
            echo 'Update notebook comment: ', $id, "\n";
        } else {
            echo 'Can not Update notebook comment: ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_recent($notebook_id)
{
    $comment = TeamWorkPm::factory('comment/notebook');
    try {
        $comments = $comment->getRecent($notebook_id);
        foreach ($comments as $c) {
            print_r($c);
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $comment = TeamWorkPm::factory('comment/notebook');
    try {
        $comment = $comment->get($id);
        print_r($comment);
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_delete($id)
{
    $comment = TeamWorkPm::factory('comment/notebook');
    try {
        if ($comment->delete($id)) {
            echo 'Delete notebook comment ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function get_first_notebook_comment()
{
    $notebook_id = get_first_notebook();
    $comment = TeamWorkPm::factory('comment/notebook');
    $comments = $comment->getRecent($notebook_id);
    foreach ($comments as $c) {
        return $c->id;
    }
}