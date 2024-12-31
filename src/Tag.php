<?php

declare(strict_types=1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model as Resource;
use TeamWorkPm\Rest\Response\Model as Response;

class Tag extends Resource
{
    protected ?string $parent = 'tag';

    protected ?string $action = 'tags';

    protected string|array $fields = 'tags';

    protected const RESOURCES = [
        'projects',
        'tasks',
        'milestones',
        'messages',
        'timelogs',
        'files',
        'users',
        'companies',
        'notebooks',
        'links'
    ];

    /**
     * List All Tags for a Resource
     *
     * @param string $resource
     * @param int $id
     * @return Response
     */
    public function allFor(string $resource, int $id): Response
    {
        $resource = $this->getResourceName($resource);

        return $this->fetch("$resource/$id/$this->action");
    }

    /**
     * Remove Tags on a Resource
     *
     * @param string $resource
     * @param integer $id
     * @return bool
     */

    public function removeTo(string $resource, int $id, int|string|array $data): bool
    {
        return $this->updateTo($resource, $id, $data, [
            'removeProvidedTags' => true
        ]);
    }

    /**
     * Add Tags on a Resource
     *
     * @param string $resource
     * @param integer $id
     * @return bool
     */
    public function addTo(string $resource, int $id, int|string|array $data): bool
    {
        return $this->updateTo($resource, $id, $data);
    }

    private function updateTo(string $resource, int $id, int|string|array $data, iterable $opts = []): bool
    {
        $resource = $this->getResourceName($resource);
        if (is_array($data)) {
            if (is_array_of_int($data)) {
                $data = ['tagIds' => implode(',', $data)];
            } else {
                $data = ['tags' => ['content' => implode('', $data)]];
            }
        } else        if (is_int($data)) {
            $data = ['tagIds' => $data];
        } else  {
            $data = ['tags' => ['content' => $data]];
        }

        foreach ($opts as $key => $value) {
            $data[$key] = $value;
        }

        return $this
            ->notUseFields()
            ->put("$resource/$id/$this->action", $data);
    }

    private function getResourceName(string $resource): string
    {
        $resource = match ($resource) {
            'people' => 'users',
            'time_entries' => 'timelogs',
            default => $resource,
        };
        if (!in_array($resource, static::RESOURCES, true)) {
            throw new Exception('Invalid resource type: ' . $resource);
        }
        return $resource;
    }
}
