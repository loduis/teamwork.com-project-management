<?php

class TeamWorkPm_Response_JSON extends TeamWorkPm_Response_Model
{
    public function parse($data, $headers)
    {
        $source = json_decode($data);
        if (!empty($source->STATUS)) {
            unset($source->STATUS);
        }
        $this->_string = json_encode($source);
        // elminamos el contenedor
        $this->_object = self::_camelizeObject(current($source));
        return $this;
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
}