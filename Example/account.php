<?php

require './bootstrap.php';

// prepare test
test_bootstrap();

function test_get() {
    $account = TeamWorkPm::factory('Account');
    try {
        echo '------------------TEST GET---------------------', "\n";
        $details = $account->get();
        print_r($details);
    } catch (Exception $e) {
        print_r($e);
    }
}

function test_authenticate() {
    $account = TeamWorkPm::factory('Account');
    try {
        echo '------------------TEST AUTHENTICATE---------------------', "\n";
        $details = $account->authenticate();
        print_r($details);
    } catch (Exception $e) {
        print_r($e);
    }
}
