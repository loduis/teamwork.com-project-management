<?php

class TestCase extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        TeamWorkPm::setAuth(API_COMPANY, API_KEY);
        TeamWorkPm::setFormat(API_FORMAT);
    }
}