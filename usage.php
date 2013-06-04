<?php

require './TeamWorkPm.php';

// START configurtion
const API_KEY = 'horse48street';

try {
    // set your keys
    TeamWorkPm::setAuth(API_KEY);

    // create an project
    $project = TeamWorkPm::factory('project');
    $project_id = $project->save(array(
        'name'=> 'This is an test project',
        'description'=> 'Bla, Bla, Bla'
    ));
    // create one people and add to project
    $people = TeamWorkPm::factory('people');
    $person_id = $people->save(array(
        'first_name'  => "Test",
        'last_name'   => 'User',
        'user_name'     => 'test',
        'email_address' => 'email@hotmail.com',
        'password'      => 'foo123',
        'project_id'    => $project_id
    ));
    // create milestone
    $milestone = TeamWorkPm::factory('milestone');
    $milestone_id = $milestone->save(array(
        'project_id'            => $project_id,
        'responsible_party_ids' => $person_id,
        'title'                 => 'Test milestone',
        'description'           => 'Bla, Bla, Bla',
        'deadline'              => date('Ymd', strtotime('+10 day'))
    ));
    // create one task list
    $taskList = TeamWorkPm::factory('task/list');
    $task_list_id = $taskList->save(array(
        'project_id'  => $project_id,
        'milestone_id' => $milestone_id,
        'name'        => 'My first task list',
        'description' => 'Bla, Bla'
    ));
    // create one task
    $task = TeamWorkPm::factory('task');
    $task_id = $task->save(array(
        'task_list_id' => $task_list_id,
        'content'      => 'Test Task',
        'notify'       => false,
        'description'  => 'Bla, Bla, Bla',
        'due_date'     => date('Ymd', strtotime('+10 days')),
        'start_date'   => date('Ymd'),
        'private'      => false,
        'priority'     => 'high',
        'estimated_minutes' => 1000,
        'responsible_party_id' => $person_id,
    ));
    // add time to task
    $time = TeamWorkPm::factory('time');
    $time_id = $time->save(array(
        'task_id'     => $task_id,
        'person_id'   => $person_id, // this is a required field
        'description' => 'Test Time',
        'date'  => date('Ymd'),
        'hours'     => 5,
        'minutes' => 30,
        'time' => '08:30',
        'isbillable' => false
    ));

    echo 'Project Id: ', $project_id, "\n";
    echo 'Person Id: ', $person_id, "\n";
    echo 'Milestone Id: ', $milestone_id, "\n";
    echo 'Task List Id: ', $task_list_id, "\n";
    echo 'Task Id: ', $task_id, "\n";
    echo 'Time id: ', $time_id, "\n";

} catch (Exception $e) {
    print_r($e);
}