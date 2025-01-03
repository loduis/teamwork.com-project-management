<?php

namespace TeamWorkPm\Tests;

class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function get()
    {
        $account = $this->factory('account')->get();
        $this->assertEquals($account->id, '1046798');
        $this->assertEquals($account->companyname, "Php's Company");
    }

    /**
     * @test
     */
    public function authenticate()
    {
        $account = $this->factory('account')->authenticate();
        $this->assertEquals($account->url, 'https://phpscompany.teamwork.com/');
        $this->assertEquals($account->code, 'phpscompany');
    }
}
