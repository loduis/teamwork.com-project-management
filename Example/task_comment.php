<?php

require_once './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('insert', 'get_recent'))) {
        return get_first_task();
    } else {
        return get_first_task_comment();
    }
});

function test_insert($task_id)
{
    $comment = TeamWorkPm::factory('Comment/Task');
    try {
       $data = array(
          'resource_id'=> $task_id,
          'body'=> 'Comment, bla, bla, comment'
        );
        $id = $comment->insert($data);
        echo 'INSERT COMMENT TASK: ', $id, "\n";
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_update($id)
{
    $comment = TeamWorkPm::factory('comment/task');
    try {
        $data = array(
            'id'=>$id,
            'body'=> 'Update this body'
        );
        if ($comment->update($data)) {
            echo 'Update task comment: ', $id, "\n";
        } else {
            echo 'Can not Update task comment: ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_recent($task_id)
{
    $comment = TeamWorkPm::factory('comment/task');
    try {
        $comments = $comment->getRecent($task_id);
        foreach ($comments as $c) {
            print_r($c);
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $comment = TeamWorkPm::factory('comment/task');
    try {
        $comment = $comment->get($id);
        print_r($comment);
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_delete($id)
{
    $comment = TeamWorkPm::factory('comment/task');
    try {
        if ($comment->delete($id)) {
            echo 'Delete task comment ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function get_first_task_comment()
{
    $task_id = get_first_task();
    $comment = TeamWorkPm::factory('comment/task');
    $comments = $comment->getRecent($task_id);
    foreach ($comments as $c) {
        return $c->id;
    }
}