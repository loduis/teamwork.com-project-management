<?php

class TeamWorkPm_Response_JSON extends TeamWorkPm_Response_Model
{

    public function parse($data, array $headers)
    {
        $source = json_decode($data);
        $errors = $this->_getJsonErrors();
        if (!$errors) {
            $status = empty($source->STATUS) ? NULL : $source->STATUS;
            if ($status) {
                unset($source->STATUS);
            }
            $this->_string = json_encode($source);
            $errors = NULL;
            if ($status === 'OK') {
                if ($headers['Method'] === 'POST') {
                    return !empty($headers['id']) ? $headers['id'] : TRUE;
                }  elseif ($headers['Method'] === 'PUT' || $headers['Method'] === 'DELETE') {
                    return TRUE;
                } else {
                    $this->_object = self::_camelizeObject(current($source));
                    return $this;
                }
            } else {
                $errors = $source->MESSAGE;
            }
        }
        throw new TeamWorkPm_Exception(array(
            'Message'=>$errors,
            'Response'=> $data,
            'Headers'=> $headers
        ));
    }

    protected function _getContent()
    {
        $result    = '';
        $pos       = 0;
        $strLen    = strlen($this->_string);
        $indentStr = '  ';
        $newLine   = "\n";

        for($i = 0; $i <= $strLen; $i++) {

            // Grab the next character in the string
            $char = substr($this->_string, $i, 1);

            // If this character is the end of an element,
            // output a new line and indent the next line
            if($char == '}' || $char == ']') {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if ($char == ',' || $char == '{' || $char == '[') {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
        }

        return $result;
    }

    protected static function _camelizeObject($source)
    {
        $destination = new stdClass();
        foreach ($source as $key=>$value) {
            $key = self::_camelize($key);
            $destination->$key = is_scalar($value) ? $value : self::_camelizeObject($value);
        }
        return $destination;
    }

    private function _getJsonErrors()
    {
            switch(json_last_error())
            {
                case JSON_ERROR_DEPTH:
                    return 'Maximum stack depth exceeded';
                case JSON_ERROR_CTRL_CHAR:
                    return 'Unexpected control character found';
                case JSON_ERROR_SYNTAX:
                    echo 'Syntax error, malformed JSON';
                case JSON_ERROR_NONE:
                    return NULL;
            }

    }
}