<?php

abstract class TeamWorkPm_Rest_Model
{
    /**
     * Maneja las instancias de clases
     * creanda en el projecto
     * @var array
     */
    private static $_instances = array();
    /**
     * Es una instancia a la clase que maneja
     * las conexiones del api con curl
     * @var TeamWorkPm_Rest
     */
    private $_rest = NULL;
    /**
     * Es el elemento padre que contiene
     * los demas elementos xml o json de los paramentros
     * del put y del post
     * @var string
     */
    protected $_parent = NULL;
    /**
     * Es el comnun recurso que se debe ejecutar
     * @var string
     */
    protected $_action = NULL;
    /**
     * Almacena los campos del objeto
     * @var array
     */
    protected $_fields = array();
    /**
     *
     * @var string
     */
    private $_hash = NULL;

    final private function  __construct($company, $key, $class, $hash)
    {
        $this->_rest   = new TeamWorkPm_Rest($company, $key);
        $this->_hash = $hash;
        $this->_parent = strtolower(str_replace(
          array('TeamWorkPm_', '_'),
          array('', '-'),
          $class
        ));
        $this->_action = str_replace('-', '_', $this->_parent);
        // pluralize
        if (substr($this->_action, -1) === 'y') {
            $this->_action = substr($this->_action, 0, -1) . 'ies';
        } else {
            $this->_action .= 's';
        }
        if (method_exists($this, '_init')) {
            $this->_init();
        }
        //configure request para put y post fields
        $this->_rest->getRequest()
                    ->setParent($this->_parent)
                    ->setFields($this->_fields);

    }

    final public function  __destruct()
    {
        unset (self::$_instances[$this->_hash]);
    }

    final protected function __clone ()
    {

    }

    /**
     *
     * @param string $company
     * @param string $key
     * @return TeamWorkPm_Model
     */
    final public static function getInstance($company, $key)
    {
        $class = get_called_class();
        $hash = md5($class . '-' . $company . '-' . $key);
        if (!isset(self::$_instances[$hash])) {
            self::$_instances[$hash] = new $class($company, $key, $class, $hash);
        }

        return self::$_instances[$hash];
    }

    /*------------------------------
            API REST METHOD
     ------------------------------*/

    final protected function _post($action, array $request = array())
    {
        return $this->_rest->post($action, $request);
    }

    final protected function _put($action, array $request = array())
    {
        return $this->_rest->put($action, $request);
    }

    final protected function _get($action, $request = null)
    {
        return $this->_rest->get($action, $request);
    }

    final protected function _delete($action)
    {
        return $this->_rest->delete($action);
    }

    final protected function _upload($action, $request)
    {
        return $this->_rest->upload($action, $request);
    }
}