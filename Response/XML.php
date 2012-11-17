<?php
namespace TeamWorkPm\Response;

class XML extends Model
{
    /**
     * Parsea un string type xml
     *
     * @param string $data
     * @param type $headers
     * @return mixed [bool, int, TeamWorkPm\Response\XML]
     * @throws \TeamWorkPm\Exception
     */
    public function parse($data, array $headers)
    {
        libxml_use_internal_errors(true);
        $this->_string = $data;
        $source = simplexml_load_string($data);
        $errors = $this->_getXmlErrors($source);
        //echo "\n", $data, "\n";
        if ($source) {
            if ($headers['Status'] === 201 || $headers['Status'] === 200) {
                switch($headers['Method']) {
                    case 'UPLOAD':
                        if (!empty($source->ref)) {
                            return (string) $source->ref;
                        }
                        break;
                    case 'POST':
                        if (!empty($headers['id'])) {
                            return $headers['id'];
                        } else {
                            $property = 0;
                            $value = (int) $source->$property;
                            // this case the fileid
                            if ($value > 0) {
                                return $value;
                            }
                        }
                        break;
                     case 'PUT':
                     case 'DELETE':
                        return TRUE;
                     default:
                        if (!empty($source->files->file)) {
                            $source = $source->files->file;
                            $isArray = TRUE;
                        } elseif (!empty($source->notebooks->notebook)) {
                            $source = $source->notebooks->notebook;
                            $isArray = TRUE;
                        }  elseif(!empty($source->project->links)) {
                            $source = $source->project->links;
                              $isArray = TRUE;
                        } else {
                            $attrs = $source->attributes();
                            $isArray = !empty($attrs->type) && (string) $attrs->type === 'array';
                        }
                        $this->_headers = $headers;

                        $_this = self::_toStdClass($source, $isArray);

                        foreach ($_this as $key=>$value) {
                            $this->$key = $value;
                        }
                        return $this;
                }
            } else {
                if (!empty($source->error)) {
                    foreach($source as $error) {
                        $errors .= $error ."\n";
                    }
                } else {
                    $property = 0;
                    $errors .= $source->$property;
                }
            }
        }
        throw new \TeamWorkPm\Exception(array(
            'Message'=> $errors,
            'Response'=> $data,
            'Headers'=> $headers
        ));
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
    private static function _toStdClass(\SimpleXMLElement $source, $isArray = FALSE)
    {
        $destination = $isArray ? array() : new \stdClass();
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
                        if (is_array($destination)) {
                            $destination[$key] = self::_toStdClass($value, TRUE);
                        } else {
                            $destination->$key = self::_toStdClass($value, TRUE);
                        }
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

    private function _getXmlErrors($xml)
    {
        $errors = '';
        foreach (libxml_get_errors() as $error) {
            $errors .= $this->getXmlError($error, $xml) . "\n";
        }
        libxml_clear_errors();
        return $errors;
    }

    private function getXmlError($error, $xml)
    {
        $return  = $xml[$error->line - 1] . "\n";
        $return .= str_repeat('-', $error->column) . "^\n";

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
             case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }

        $return .= trim($error->message) .
                   "\n  Line: $error->line" .
                   "\n  Column: $error->column";

        if ($error->file) {
            $return .= "\n  File: $error->file";
        }

        return "$return\n\n--------------------------------------------\n\n";
    }

}