<?php

class TeamWorkPm_Response_XML extends TeamWorkPm_Response_Model
{
    /**
     * Parsea un string type xml
     *
     * @param string $data
     * @param type $headers
     * @return \TeamWorkPm_Response_XML
     * @throws TeamWorkPm_Exception
     */
    public function parse($data, $headers = array())
    {
        libxml_use_internal_errors(true);
        $this->_string = $data;
        if (!($source = simplexml_load_string($data))) {
            throw  new TeamWorkPm_Exception($this->_string);
        }
        $attrs = $source->attributes();
        $isArray = !empty($attrs->type) && (string) $attrs->type === 'array';
        $this->_object = self::_toStdClass($source, $isArray);

        return $this;
    }

    /**
     * Devuelve un xml formateado
     *
     * @return string
     */
    protected function _getContent()
    {
        $dom = new DOMDocument('1.0');
        $dom->loadXML($this->_string);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    /**
     * Convierte un objecto SimpleXMLElement a stdClass
     *
     * @param SimpleXMLElement $source
     * @param bool $isArray
     * @return stdClass
     */
    private static function _toStdClass(SimpleXMLElement $source, $isArray = FALSE)
    {
        $destination = $isArray ? array() : new stdClass();
        foreach($source as $key=>$value) {
            $key = self::_camelize($key);
            $attrs = $value->attributes();
            if (!empty($attrs->type)) {
                $type = (string) $attrs->type;
                switch($type) {
                    case 'integer':
                        $destination->$key = (int) $value;
                        break;
                    case 'boolean':
                        $value = (string) $value;
                        $destination->$key = (bool) $value === 'true';
                        break;
                    case 'array':
                        $destination->$key = self::_toStdClass($value, TRUE);
                        break;
                    default:
                        $destination->$key = (string) $value;
                        break;
                }
            } else {
                $children = $value->children();
                if (!empty($children)) {
                    if ($isArray) {
                        $i               = count($destination);
                        $destination[$i] = self::_toStdClass($value);
                    } else {
                        $destination->$key = self::_toStdClass($value);
                    }
                } else {
                    $destination->$key = (string) $value;
                }
            }
        }

        return $destination;
    }
}