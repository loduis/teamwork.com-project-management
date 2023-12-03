<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Company;

class CompanyTest extends TestCase
{
    public function testResolvePath(): void
    {
        $this->assertEquals('companies', Company::resolvePath());
    }

    public function testAll(): void
    {
        $companies = Company::all();
        $this->assertCount(1, $companies);
    }

    public function testGet(): void
    {
        $company = Company::get(1370007);
        $this->assertEquals(1370007, $company->id);
        $this->assertEquals("Php's Company", $company->name);
        $this->assertEquals('1370007-php-s-company', $company->companyNameUrl);
        $this->assertEquals('1370007-php-s-company', $company['company_name_url']);
        $this->assertTrue($company->canSeePrivate);
        $this->assertTrue($company['can_see_private']);
        $this->assertEquals('2023-11-29T11:54:33Z', $company->createdOn);
    }
}
