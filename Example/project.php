<?php

require_once '../autoload.php';
// ahora puede cambiar el formato con que se traen los datos del api
// los posibles valores son xml y json, por defecto se traen en formato json
#TeamWorkPm::setFormat('xml');
#TeamWorkPm::setFormat('json');
// instancias de clase
$project = TeamWorkPm::factory('Project');
$list    = TeamWorkPm::factory('Todo/List');
//$response = $Project->get(16532);
try {
    $projects = $project->getAll();
    # si desear guardar los datos en un archivo
    $projects->save('data/projects');
    echo '-------------NOTACION OBJETO----------------', "\n";
    foreach ($projects as $p) {
        echo '--------------------------------', "\n";
        echo 'Project: ', $p->name, "\n";
        $lists = $list->getByProjectId($p->id);
        foreach ($lists as $l) {
            echo 'List: ', $l->name, "\n";
            $fl = $list->get($l->id);
            foreach ($fl->todoItems as $i) {
                echo 'Item: ', $i->content, "\n";
            }
        }
    }
    echo '-------------NOTACION ARRAY----------------', "\n";
    foreach ($projects->toArray() as $p) {
        echo '--------------------------------', "\n";
        echo 'Project: ', $p['name'], "\n";
        $lists = $list->getByProjectId($p['id']);
        foreach ($lists->toArray() as $l) {
            echo 'List: ', $l['name'], "\n";
            $fl = $list->get($l['id'])->toArray();
            foreach ($fl['todoItems'] as $i) {
                echo 'Item: ', $i['content'], "\n";
            }
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}