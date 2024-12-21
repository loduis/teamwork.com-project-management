<?php

namespace TeamWorkPm;

use TeamWorkPm\Rest\GetByProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/companies/get-companies-json
 */
class Company extends Model
{
    use GetByProjectTrait;

    protected ?string $parent = 'company';

    protected ?string $action = 'companies';

    protected string|array $fields = 'companies';
}
