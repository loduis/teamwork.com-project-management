<?php

require_once './bootstrap.php';

test_bootstrap(function ($command) {
    if (in_array($command, array('insert', 'get_recent'))) {
        return get_first_file();
    } else {
        return get_first_file_comment();
    }
});

function test_insert($file_id)
{
    $comment = TeamWorkPm::factory('comment/file');
    try {
       $file = TeamWorkPm::factory('file');
       $file = $file->get($file_id);
       $version_id = $file->versionId;
       $data = array(
          'resource_id'=> $version_id,
          'body'=> 'Comment, bla, bla, comment'
        );
        $id = $comment->insert($data);
        echo 'Insert file comment: ', $id, "\n";
    } catch (Exception $e) {
        //print_r($e);
        echo $e->getMessage(), "-----\n";
    }
}

function test_update($id)
{
    $comment = TeamWorkPm::factory('comment/file');
    try {
        $data = array(
            'id'=>$id,
            'body'=> 'Update this body . ' .rand(1, 10)
        );
        if ($comment->update($data)) {
            echo 'Update file comment: ', $id, "\n";
        } else {
            echo 'Can not Update file comment: ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get_recent($file_id)
{
    $comment = TeamWorkPm::factory('comment/file');
    try {
        $comments = $comment->getRecent($file_id);
        foreach ($comments as $c) {
            print_r($c);
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_get($id)
{
    $comment = TeamWorkPm::factory('comment/file');
    try {
        $comment = $comment->get($id);
        print_r($comment);
    } catch(Exception $e) {
        print_r($e);
    }
}

function test_delete($id)
{
    $comment = TeamWorkPm::factory('comment/file');
    try {
        if ($comment->delete($id)) {
            echo 'Delete file comment ', $id, "\n";
        }
    } catch(Exception $e) {
        print_r($e);
    }
}

function get_first_file_comment()
{
    $file_id = get_first_file();
    $comment = TeamWorkPm::factory('comment/file');
    $comments = $comment->getRecent($file_id);
    foreach ($comments as $c) {
        return $c->id;
    }
}