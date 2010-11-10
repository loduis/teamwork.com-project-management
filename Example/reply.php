<?php

require_once '../autoload.php';

$reply = TeamWorkPm::factory('Reply');
//$response = $reply->getByMessageId(22262);
//print_r($response);
$reply->delete(51862);
/*
$reply->save(array(
    'message_id'=>22220,
    'body'=>'Estamos dando respuesta'
));*/
//$reply->delete(51239);