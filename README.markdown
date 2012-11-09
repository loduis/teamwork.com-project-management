TeamWorkPm PHP Api
==================

  Require php 5.3 +

Installation
------------

    $ git clone git://github.com/loduis/TeamWorkPmPhpApi.git TeamWorkPm

Using Api
---------

    require './TeamWorkPm.php';

    // START configurtion
    const API_COMPANY = 'phpapi2';
    const API_KEY = 'horse48street';
    const API_FORMAT = 'json';
    // set your keys
    TeamWorkPm::setAuth(API_COMPANY, API_KEY);
    // set format not need, by default json format api
    TeamWorkPm::setFormat(API_FORMAT);

    $project = TeamWorkPm::factory('project');
    // where 'project' is the file Project.php
    // for task list is task/list

    $data = array(
      'name'=> 'This is an test project',
      'description'=> 'Bla, Bla, Bal'
    );
    try {
      $id = $project->insert($data);
      echo 'Project: ', $id, "\n";
    } catch (Exception $e) {
      print_r($e);
    }

View the Example folder for more details