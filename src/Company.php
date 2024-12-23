<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\Project\GetByTrait as GetByProjectTrait;

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
