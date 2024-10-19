<?php

namespace TeamWorkPm\Tests;

class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function get()
    {
        $account = $this->tpm('account')->get();
        $this->assertEquals($account->id, '1046798');
        $this->assertEquals($account->companyname, "Php's Company");
    }

    /**
     * @test
     */
    public function authenticate()
    {
        $account = $this->tpm('account')->authenticate();
        $this->assertEquals($account->url, 'https://phpscompany.teamwork.com/');
        $this->assertEquals($account->code, 'phpscompany');
    }
}
