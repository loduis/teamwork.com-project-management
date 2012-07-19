<?php

require_once '../autoload.php';

$message = TeamWorkPm::factory('Message');
$data = array(
  'project_id'=>16538,
   'title'=>'New Message',
   'body'=>'Prueba',
   'category_id'=>16538
);
if ($message->save($data)) {
    echo 'El Mensaje fue creada';
} else {
    echo 'No se pudo crear el Mensaje';
}