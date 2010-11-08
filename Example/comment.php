<?php

require_once '../autoload.php';

$comment = TeamWorkPm::factory('Comment/Item');
/*
$comment->save(array(
   'resource_id'=>25669,
   'body'=>'Esto es una preuba de comentario'
));*/
$response = $comment->getRecent(226851, array('page'=>1));
print_r($response);
/*
$json = json_format(json_encode($response));
echo $json;*/
/*
$comment->save(array(
    'id'=>61894,
    'body'=>'Modificando el comentario'
));*/
//$comment->delete(61894);
