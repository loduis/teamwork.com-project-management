<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Account;

class AccountTest extends TestCase
{
    public function testResolvePath()
    {
        $this->assertEquals('account', Account::resolvePath());
    }

    public function testGet()
    {
        $account = Account::get();
        $this->assertArrayHasKey('name', $account);
        $this->assertArrayHasKey('URL', $account);
        $this->assertInstanceOf(Account::class, $account);
    }
}
