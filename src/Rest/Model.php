<?php

namespace TeamWorkPm\Rest;

use TeamWorkPm\Rest;

/**
 * @method void init()
 */
abstract class Model
{
    /**
     * Manages the instances of classes created in the project
     *
     * @var array
     */
    private static $instances = [];

    /**
     * It is an instance of the class that handles api connections with curl
     *
     * @var \TeamWorkPm\Rest
     */
    protected $rest = null;

    /**
     * It is the parent element that contains the other xml or json elements of the put and post parameters
     *
     * @var string
     */
    protected $parent = null;

    /**
     * It is the common resource to be executed
     *
     * @var string
     */
    protected $action = null;

    /**
     * Stores the object fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    private $hash = null;

    /**
     * @param string $url
     * @param string $key
     * @param string $class
     * @param string $hash
     *
     * @throws \TeamWorkPm\Exception
     */
    final private function __construct($url, $key, $class, $hash)
    {
        $this->rest = new Rest($url, $key);
        $this->hash = $hash;
        $this->parent = strtolower(
            str_replace(
                ['TeamWorkPm\\', '\\'],
                ['', '-'],
                $class
            )
        );
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if ($this->action === null) {
            $this->action = str_replace('-', '_', $this->parent);
            // pluralize
            if (substr($this->action, -1) === 'y') {
                $this->action = substr($this->action, 0, -1) . 'ies';
            } else {
                $this->action .= 's';
            }
        }
        // configure request for put and post fields
        $this->rest->getRequest()
            ->setParent($this->parent)
            ->setFields($this->fields);
    }

    /**
     * @codeCoverageIgnore
     */
    final public function __destruct()
    {
        unset(self::$instances[$this->hash]);
    }

    /**
     * @codeCoverageIgnore
     */
    final protected function __clone()
    {
    }

    /**
     * @param string $url
     * @param string $key
     *
     * @return \TeamWorkPm\Model
     */
    final public static function getInstance($url, $key)
    {
        $class = get_called_class();
        $hash = md5($class . '-' . $url . '-' . $key);
        if (!isset(self::$instances[$hash])) {
            self::$instances[$hash] = new $class($url, $key, $class, $hash);
        }

        return self::$instances[$hash];
    }
}
