<?php

require_once '../autoload.php';

$People = TeamWorkPm::factory('People');
$data = array(
    'first_name'=> 'Lucas',
    'last_name'=>'Madariaga',
    'email'=>'loduis@hotmail.com',
    'email_address'=> 'loduis@hotmail.com',
    'user_name'=>'lucas',
    'password'=>'loquis81',
    'company_id'=> 9463,
    'administrator'=>false,
    'title'=>'Mr',
    'sendWelcomeEmail'=>'no'
);

if ($People->save($data)) {
    echo 'User save';
}