<?php

require_once '../autoload.php';

$list = TeamWorkPm::factory(TeamWorkPm::TODO_LIST);
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