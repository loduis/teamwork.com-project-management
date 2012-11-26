<?php
namespace TeamWorkPm\Rest;

abstract class Model
{
    /**
     * Maneja las instancias de clases
     * creanda en el projecto
     * @var array
     */
    private static $instances = array();

    /**
     * Es una instancia a la clase que maneja
     * las conexiones del api con curl
     * @var \TeamWorkPm\Rest
     */
    protected $rest = null;
    /**
     * Es el elemento padre que contiene
     * los demas elementos xml o json de los paramentros
     * del put y del post
     * @var string
     */
    protected $_parent = null;
    /**
     * Es el comnun recurso que se debe ejecutar
     * @var string
     */
    protected $_action = null;
    /**
     * Almacena los campos del objeto
     * @var array
     */
    protected $_fields = array();
    /**
     *
     * @var string
     */
    private $hash = null;

    final private function  __construct($company, $key, $class, $hash)
    {
        $this->rest   = new \TeamWorkPm\Rest($company, $key);
        $this->hash   = $hash;
        $this->_parent = strtolower(str_replace(
          array('TeamWorkPm\\', '\\'),
          array('', '-'),
          $class
        ));
        if (method_exists($this, '_init')) {
            $this->_init();
        }
        if (null === $this->_action) {
            $this->_action = str_replace('-', '_', $this->_parent);
            // pluralize
            if (substr($this->_action, -1) === 'y') {
                $this->_action = substr($this->_action, 0, -1) . 'ies';
            } else {
                $this->_action .= 's';
            }
        }
        //configure request para put y post fields
        $this->rest->getRequest()
                    ->setParent($this->_parent)
                    ->setFields($this->_fields);

    }

    /**
     * @codeCoverageIgnore
     */
    final public function  __destruct()
    {
        unset (self::$instances[$this->hash]);
    }

    /**
     * @codeCoverageIgnore
     */
    final protected function __clone ()
    {

    }

    /**
     *
     * @param string $company
     * @param string $key
     * @return TeamWorkPm\Model
     */
    final public static function getInstance($company, $key)
    {
        $class = get_called_class();
        $hash = md5($class . '-' . $company . '-' . $key);
        if (!isset(self::$instances[$hash])) {
            self::$instances[$hash] = new $class($company, $key, $class, $hash);
        }

        return self::$instances[$hash];
    }
}