<?php

require_once '../autoload.php';

/*

 <time-entry>
	<description>{description}</description>
	<person-id>{person-id}</person-id>
	<date>{date in YYYYMMDD form}</date>
	<hours>{hours}</hours>
	<minutes>{minutes}</minutes>
	<time>{Time of time logged in HH:MM form}</time>
	<isbillable type="boolean">{yes|no}</isbillable>
</time-entry>

*/


$time = TeamWorkPm::factory('Time');
# insertando en un todo list
$response = $time->insert(array(
  'todo_item_id'=> 224725, # id de un todo item
  'description'=> 'Esto es una prueba se factura.',
  'person_id'=> 19496, # es el id de la persona no el api key
  'date'=> date('Ymd'), # fecha en la que se registra
  'hours'=> 5, # horas
  'minutes'=>0,
  'time'=> '08:00',
  'isbillable'=>TRUE
));
#print_r($response);
echo $response;