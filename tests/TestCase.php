<?php

class TestCase extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        TeamWorkPm\Auth::set(API_COMPANY, API_KEY);
        TeamWorkPm\Rest::setFormat(API_FORMAT);
    }
}