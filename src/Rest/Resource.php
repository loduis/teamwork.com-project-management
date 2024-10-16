<?php

namespace TeamWorkPm\Rest;

use BadMethodCallException;
use TeamWorkPm\Exception;

/**
 * @method void init()
 */
abstract class Resource
{
    /**
     * It is an instance of the class that handles api connections with curl
     *
     * @var \TeamWorkPm\Rest\Client
     */
    protected Client $rest;

    /**
     * It is the parent element that contains the other xml or json elements of the put and post parameters
     *
     * @var string
     */
    protected ?string $parent = null;

    /**
     * It is the common resource to be executed
     *
     * @var string
     */
    protected ?string $action = null;

    /**
     * Stores the object fields
     *
     * @var array
     */
    protected array $fields = [];

    /**
     * @param string $url
     * @param string $key
     * @param string $class
     * @param string $hash
     *
     * @throws Exception
     */
    public function __construct($httpClient)
    {
        $this->rest   = $httpClient;
        if ($this->parent === null) {
            $this->parent = strtolower(str_replace(
                ['TeamWorkPm\\', '\\'],
                ['', '-'],
                static::class
            ));
        }
        $this->init();
        if (null === $this->action) {
            $this->action = str_replace('-', '_', $this->parent);
            // pluralize
            if (substr($this->action, -1) === 'y') {
                $this->action = substr($this->action, 0, -1) . 'ies';
            } else {
                $this->action .= 's';
            }
        }
        // configure request for put and post fields
        $this->rest->configRequest(
            $this->parent,
            $this->fields
        );
    }

    /**
     * @codeCoverageIgnore
     */
    final protected function __clone()
    {
    }

    protected function init()
    {
    }

    public function __call($name, $arguments)
    {
        if ($name === 'all' && method_exists($this, 'getAll')) {
            return $this->getAll(...$arguments);
        }
        if ($name === 'getAll' && method_exists($this, 'all')) {
            return $this->all(...$arguments);
        }

        if ($name === 'first') {
            $entries = [];
            if (method_exists($this, 'getAll')) {
                $entries = $this->getAll(...$arguments);
            } elseif (method_exists($this, 'all')) {
                $entries = $this->all(...$arguments);
            }

            if (count($entries) > 0) {
                return $entries[0];
            }

            return $entries;
        }

        throw new BadMethodCallException("No exists method: $name");
    }
}
