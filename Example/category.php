<?php

require_once '../autoload.php';

$category = TeamWorkPm::factory('Category/Message');
if (!$category->save(array(
    'project_id' => 16538,
    'name'=> 'Mas'
))) {
    echo $category->getErrors();
}

// CUANDO SE TRATA DE ELIMINAR UNA CATEGORIA QUE TIENE
// ELEMENTOS RELACIONADO EL CODIGO HTTP QUE ME DEVUELVE EL API ES UN 422
// MY DIFERENTE A LO QUE DICE LA DOCUMENTACION DEL API UN 409
// ESTE ES EL MENSAJE DE ERROR
// The custom error module does not recognize this error

/*
if ($category->delete(34994)) {
    echo 'Delete';
} else {
    echo $category->getErrors();
}*/
/*
GET BY PROJECT ID
$response = $category->getByProjectId(16538);
*/
/*
THIS IS AN INSERT
$category->save(array(
    'project_id' => 16538,
    'name'=> 'Test'
));*/