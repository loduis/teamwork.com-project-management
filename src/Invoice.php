<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\Project\ActionTrait as ProjectTrait;
use TeamWorkPm\Rest\Resource\CompleteTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/invoices/get-invoices-json
 */
class Invoice extends Model
{
    use ProjectTrait, CompleteTrait;

    protected ?string $parent = 'invoice';

    protected ?string $action = 'invoices';

    protected string|array $fields = 'invoices';

    public function addTime(int $id, string $time): bool
    {
        return $this->notUseFields()
            ->put("$this->action/$id/lineitems", [
                'lineitems' => [
                    'add' => [
                        'timelogs' => "$time"
                    ]
                ]
        ]);
    }
}
