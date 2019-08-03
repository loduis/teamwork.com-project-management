# Teamwork.com PHP Api

[![Latest Stable Version](https://poser.pugx.org/myabakus/teamworkpm/v/stable)](https://packagist.org/packages/myabakus/teamworkpm)
[![Total Downloads](https://poser.pugx.org/myabakus/teamworkpm/downloads)](https://packagist.org/packages/myabakus/teamworkpm)

## Installation

```bash
composer require myabakus/teamworkpm
```

## Using the Api

This is very easy use the method: save, insert, update, delete

```php
// START configurtion
const API_KEY = 'horse48street';
const API_URL = 'http://yourcustomdomain.com'; // only required if you have a custom domain

try {
	// set your keys
	// if you do not have a custom domain:
	\TeamWorkPm\Auth::set(API_KEY);
	// if you do have a custom domain:
	// TeamWorkPm\Auth::set(API_URL, API_KEY);

	// create an project
	$project = \TeamWorkPm\Factory::build('project');
	$project_id = $project->save([
		'name'=> 'This is a test project',
		'description'=> 'Bla, Bla, Bla'
	]);

	// create one people and add to project
	$people = \TeamWorkPm\Factory::build('people');
	$person_id = $people->save([
		'first_name'  => 'Test',
		'last_name'   => 'User',
		'user_name'     => 'test',
		'email_address' => 'email@hotmail.com',
		'password'      => 'foo123',
		'project_id'    => $project_id
	]);

	// create on milestone
	$milestone = \TeamWorkPm\Factory::build('milestone');
	$milestone_id = $milestone->save([
		'project_id'            => $project_id,
		'responsible_party_ids' => $person_id,
		'title'                 => 'Test milestone',
		'description'           => 'Bla, Bla, Bla',
		'deadline'              => date('Ymd', strtotime('+10 day'))
	]);

	// create one task list
	$taskList = \TeamWorkPm\Factory::build('task.list');
	$task_list_id = $taskList->save([
		'project_id'  => $project_id,
		'milestone_id' => $milestone_id,
		'name'        => 'My first task list',
		'description' => 'Bla, Bla'
	]);

	// create one task
	$task = \TeamWorkPm\Factory::build('task');
	$task_id = $task->save([
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
	]);

	// add time to task
	$time = \TeamWorkPm\Factory::build('time');
	$time_id = $time->save([
		'task_id' => $task_id,
		'person_id' => $person_id,
		'description' => 'Test Time',
		'date' => date('Ymd'),
		'hours' => 5,
		'minutes' => 30,
		'time' => '08:30',
		'isbillable' => false
	]);

	echo 'Project Id: ' . $project_id . "\n";
	echo 'Person Id: ' . $person_id . "\n";
	echo 'Milestone Id: ' . $milestone_id . "\n";
	echo 'Task List Id: ' . $task_list_id . "\n";
	echo 'Task Id: ' . $task_id . "\n";
	echo 'Time id: ' . $time_id . "\n";
} catch (Exception $e) {
	print_r($e);
}
```

View the tests folder for more details
