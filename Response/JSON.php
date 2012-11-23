<?php
namespace TeamWorkPm\Response;

class JSON extends Model
{

    public function parse($data, array $headers)
    {
        $source = json_decode($data);
        $errors = $this->_getJsonErrors();
        $this->_string = $data;
        if (!$errors) {
            if ($headers['Status'] === 201 || $headers['Status'] === 200) {
                switch($headers['Method']) {
                    case 'UPLOAD':
                        if (!empty($source->pendingFile->ref)) {
                            return (string) $source->pendingFile->ref;
                        }
                        break;
                    case 'POST':
                        if (!empty($headers['id'])) {
                            return (int) $headers['id'];
                        } elseif (!empty($source->fileId)) {
                            return (int) $source->fileId;
                        } elseif ($headers['Location']) {
                            $location = $headers['Location'];
                            $id = substr($location, strrpos($location, '/') + 1);
                            $id = (int) substr($id, 0, strpos($id, '.'));
                            return $id;
                        }
                     case 'PUT':
                     case 'DELETE':
                         return true;
                     default:
                        //print_r($source);
                        if (!empty($source->STATUS)) {
                            unset($source->STATUS);
                        }
                        if (!empty($source->project->files)) {
                            $source = $source->project->files;
                        } elseif(!empty($source->project->notebooks)) {
                            $source = $source->project->notebooks;
                        } elseif(!empty($source->project->links)) {
                            $source = $source->project->links;
                        } elseif (!empty($source->messageReplies)) {
                            $match = preg_match(
                                '!messageReplies/(\d+)!',
                                $headers['X-Action']
                            );
                            if ($match) {
                                $source = current($source->messageReplies);
                            } else {
                                $source = current($source);
                            }
                        } else {
                            $source = current($source);
                        }

                        $this->_headers = $headers;
                        $this->_string = json_encode($source);
                        $_this = self::_camelizeObject($source);
                        foreach ($_this as $key=>$value) {
                            $this->$key = $value;
                        }
                        if (!empty($this->id)) {
                            $this->id = (int) $this->id;
                        }
                        return $this;
                }
            } else {
                $errors = $source->MESSAGE;
            }
        }
        throw new \TeamWorkPm\Exception(array(
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
        $destination = new \stdClass();
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
                    return 'Syntax error, malformed JSON';
                case JSON_ERROR_NONE:
                    return NULL;
            }

    }
}