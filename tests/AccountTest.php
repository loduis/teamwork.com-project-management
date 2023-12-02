<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Account;

class AccountTest extends TestCase
{
    public function testResolvePath(): void
    {
        $this->assertEquals('account', Account::resolvePath());
    }

    public function testGet(): void
    {
        $account = Account::get();
        $this->assertArrayHasKey('name', $account);
        $this->assertArrayHasKey('URL', $account);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals("Php's Company", $account->name);
        $this->assertEquals('https://phpscompany.teamwork.com/', $account->URL);
    }
}
