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



# insertando en un todo list
$response = $time->insert();
#print_r($response);
echo $response;

function test_insert($id = NULL) {
    $time = TeamWorkPm::factory('Time');
    try {
        $data = array(
          'description'=> 'Esto es una prueba se factura.',
          'person_id'=> 19496, # es el id de la persona no el api key
          'date'=> date('Ymd'), # fecha en la que se registra
          'hours'=> 5, # horas
          'minutes'=>0,
          'time'=> '08:00',
          'isbillable'=>TRUE
        );
        $time->insert($data);

    } catch (Exception $e) {
        print_r($e);
    }
}
