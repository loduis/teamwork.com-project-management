<?php

require_once '../autoload.php';

$list = TeamWorkPm::factory('Todo/List');
$response = $list->getByProjectId(16538);
print_r($response);
//$response = $list->getByProjectId(16538);
//print_r($response);

//file_put_contents('todo.json', json_encode($response));

/*
$xml = simplexml_load_file('../todos.xml');

foreach ($xml as $o) {
    $data = array(
        'project_id'=>16538,
        'name'=> strval($o->name),
    );
    $list->save($data);
}*/


/*
$data = array(
    'project_id'=>16538,
    'name'=>'Prueba 4'
);

$list->save($data);*/

//$data = $list->getByProjectId(16538);
//THIS IS AN INSERT
/*
$list->save(array(
    'project_id'=>16538,
    'name'=>'Prueba'
));*/
//THIS IS AN UPDATE
/*
$data = array(
    'id'=>46713,
    'name'=>'Prueba de actualizacion'
);
if ($list->save($data)) {
    echo 'La lista fue actualizada';
}*/
//THIS IS A DELETE
/*
if ($list->delete(46713)) {
    echo 'La Lista hasido eliminada';
} else {
    echo 'No se pudo eliminar la lista';
}*/