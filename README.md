# Teamwork.com PHP API

[![Latest Stable Version](https://poser.pugx.org/myabakus/teamworkpm/v/stable)](https://packagist.org/packages/myabakus/teamworkpm)
[![Total Downloads](https://poser.pugx.org/myabakus/teamworkpm/downloads)](https://packagist.org/packages/myabakus/teamworkpm)

This library allows you to interact with the Teamwork.com API for managing projects, tasks, milestones, people, and more. It’s designed for developers looking to automate or integrate project management processes within their PHP applications.

## Installation
To install the package, run the following command in your terminal:
```bash
composer require myabakus/teamworkpm
```

## Using the Api
In the following example, you will see how to use the API to create a project, add a person, define a milestone, create a task list, and assign a task with time tracking:

```php
require __DIR__ . '/vendor/autoload.php';

// START configuration
const API_KEY = 'horse48street';
const API_URL = 'https://yourcustomdomain.com'; // only required if you have a custom domain

try {
    // set your keys
    // if you do not have a custom domain:
    Tpm::auth(API_KEY);

    // if you do have a custom domain:
    // Tpm::auth(API_URL, API_KEY);

    // if you do have a need use different format:
    // Tpm::auth(API_URL, API_KEY, API_FORMAT);

    // create a project
    $project_id = Tpm::project()->save([
        'name' => 'This is a test project',
        'description' => 'Bla, Bla, Bla',
    ]);

    // create one people and add to project
    $person_id = Tpm::people()->save([
        'first_name' => 'Test',
        'last_name' => 'User',
        'user_name' => 'test',
        'email_address' => 'email@hotmail.com',
        'password' => 'foo123',
        'project_id' => $project_id,
    ]);

    // create a milestone
    $milestone_id = Tpm::milestone()->save([
        'project_id' => $project_id,
        'responsible_party_ids' => $person_id,
        'title' => 'Test milestone',
        'description' => 'Bla, Bla, Bla',
        'deadline' => date('Ymd', strtotime('+10 day')),
    ]);

    // create a task list
    $task_list_id = Tpm::taskList()->save([
        'project_id' => $project_id,
        'milestone_id' => $milestone_id,
        'name' => 'My first task list',
        'description' => 'Bla, Bla',
    ]);

    // create a task
    $task_id = Tpm::task()->save([
        'task_list_id' => $task_list_id,
        'content' => 'Test Task',
        'notify' => false,
        'description' => 'Bla, Bla, Bla',
        'due_date' => date('Ymd', strtotime('+10 days')),
        'start_date' => date('Ymd'),
        'private' => false,
        'priority' => 'high',
        'estimated_minutes' => 1000,
        'responsible_party_id' => $person_id,
    ]);

    // add time to task
    $time_id = Tpm::time()->save([
        'task_id' => $task_id,
        'person_id' => $person_id,
        'description' => 'Test Time',
        'date' => date('Ymd'),
        'hours' => 5,
        'minutes' => 30,
        'time' => '08:30',
        'isbillable' => false,
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

## Console
The console provides a visual interface for interacting with the API and viewing responses or debugging.

![console](https://github.com/user-attachments/assets/041dd784-fa3d-46eb-81a7-76a1b334ebf0)

Save your tests fixtures

```bash
  > stf(Tpm::me()->get())
```