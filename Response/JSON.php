<?php

class TeamWorkPm_Response_JSON extends TeamWorkPm_Response_Model
{
    public function parse($data, $headers)
    {
        $this->_string = $data;
        $this->_array = json_decode($this->_string, TRUE);
        $source  = json_decode($this->_string);
        $this->_copy($this, $source);

        return $this;
    }

    protected function _getContent()
    {
        return $this->_string;
    }

    private function _copy($destination, $source)
    {
        foreach ($source as $key=>$value) {
            $is_array = is_numeric($key);
            if (is_object($value)) {
                if ($is_array) {
                    $destination[$key] = new stdClass();
                    $this->_copy($destination[$key], $value);
                } else {
                    $destination->$key = new stdClass();
                    $this->_copy($destination->$key, $value);
                }
            } elseif ($is_array) {
                $destination[$key] = $value;
            } else {
                $destination->$key = $value;
            }
        }
    }
}