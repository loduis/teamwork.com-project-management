<?php

require_once '../autoload.php';

$category = TeamWorkPm::factory(TeamWorkPm::CATEGORY_MESSAGE);
$response = $category->getByProjectId(16538);
print_r($response);
/*
$category->save(array(
    'project_id' => 16538,
    'name'=> 'Test'
));*/