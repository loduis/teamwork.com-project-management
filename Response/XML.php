<?php

libxml_use_internal_errors(true);

class TeamWorkPm_Response_XML extends TeamWorkPm_Response_Model
{
    public function parse($data, $headers = array())
    {
        $this->_string = $data;
        preg_match_all('#(<(\S+)[^>]*>).*</\\2>#i', $data, $matches);
        foreach ($matches[2] as $tag) {
            if (strpos($tag, '-') !== false) {
                $camelize = preg_replace('/-(.)/e','strtoupper(\'$1\');', $tag);
                $data = preg_replace('#(</?)(' . $tag . ')#', '$1' . $camelize, $data);
           }
        }
        if (!($this->_object = simplexml_load_string($data))) {
            $errors = '<errors>';
            $errors .= $data;
            $errors .= '</errors>';
            $this->_string = $errors;
            $this->_object = simplexml_load_string($this->_string);
        }

        $this->_setArray($this->_object);

        return $this;
    }

    protected function _getContent()
    {
        $object = simplexml_load_string($this->_string);

        return $object->asXML();
    }
}