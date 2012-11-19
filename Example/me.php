<?php

require './bootstrap.php';

// prepare test
test_bootstrap();


function test_get()
{
    $me = TeamWorkPm::factory('me');
    try {
        $me = $me->get();
        print_r($me);
    } catch (Exception $e) {
        print_r($e);
    }
}