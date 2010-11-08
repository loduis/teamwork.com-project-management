<?php

require_once '../autoload.php';

$post = TeamWorkPm::factory('Post');
$data = array(
  'project_id'=>16538,
   'title'=>'New Message',
   'body'=>'Prueba',
   'category_id'=>16538
);
if ($post->save($data)) {
    echo 'El Mensaje fue creada';
} else {
    echo 'No se pudo crear el Mensaje';
}