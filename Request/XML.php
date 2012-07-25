<?php

class TeamWorkPm_Request_XML extends TeamWorkPm_Request_Model
{
    private $_doc;

    public function  __construct()
    {
        $this->_doc               = new DOMDocument();
        $this->_doc->formatOutput = true;
    }

    protected function _getParameters($parameters)
    {
        if (!empty($parameters) && is_array($parameters)) {
            $wrapper = $this->_doc->createElement($this->_getWrapper());
            $parent = $this->_doc->createElement($this->_getParent());
            if ($this->_actionInclude('/reorder')) {
                $parent->setAttribute('type', 'array');
                foreach ($parameters as $id) {
                    $element = $this->_doc->createElement($this->_parent);
                    $item = $this->_doc->createElement('id');
                    $item->appendChild($this->_doc->createTextNode($id));
                    $element->appendChild($item);
                    $parent->appendChild($element);
                }
            } else {
                foreach ($this->_fields as $field=>$options) {

                    $value   = $this->_getValue($field, $options, $parameters);
                    $element = $this->_doc->createElement($field);
                    if (isset ($options['attributes'])) {
                        foreach ($options['attributes'] as $name=>$type) {
                            $this->_setDefaultValueIfIsNull($type, $value);
                            if (null !== $value) {
                                $element->setAttribute($name, $type);
                                if ($name == 'type') {
                                    if ($type == 'array') {
                                        $internal = $this->_doc->createElement($options['element']);
                                        foreach ($value as $v) {
                                            $internal->appendChild($this->_doc->createTextNode($v));
                                            $element->appendChild($internal);
                                        }
                                    } else {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if (null !== $value) {
                        if (is_bool($value)) {
                            $value = var_export($value, true);
                        }
                        $element->appendChild($this->_doc->createTextNode($value));
                        !empty($options['sibling']) ?
                            $wrapper->appendChild($element) :
                            $parent->appendChild($element);
                    }
                }
            }
            $wrapper->appendChild($parent);
            $this->_doc->appendChild($wrapper);

            $parameters = $this->_doc->saveXML();
        } else {
            $parameters = NULL;
        }

        return $parameters;
    }

    protected function _getWrapper()
    {
        return 'request';
    }
}