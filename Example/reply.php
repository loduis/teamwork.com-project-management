<?php

require_once '../autoload.php';

$reply = TeamWorkPm::factory('Reply');
/*
$reply->save(array(
    'message_id'=>22220,
    'body'=>'Estamos dando respuesta'
));*/
$reply->delete(51239);