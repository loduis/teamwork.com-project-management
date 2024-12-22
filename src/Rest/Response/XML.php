<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Response;

use SimpleXMLElement;
use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

/**
 * @template TKey of array-key
 * @template TValue
 * @extends Model<TKey,TValue>
 */
class XML extends Model
{
    /**
     * Parsea un string type xml
     *
     * @param string $data
     * @param type $headers
     * @return mixed [bool, int, TeamWorkPm\Response\XML]
     * @throws Exception
     */
    public function parse($data, array $headers)
    {
        libxml_use_internal_errors(true);
        $this->originalString = $this->string = $data;
        $source = simplexml_load_string($data);
        $errors = $this->getXmlErrors($source);
        if ($source) {
            if (in_array($headers['Status'], [201, 200, 204])) {
                switch ($headers['Method']) {
                    case 'UPLOAD':
                        if (!empty($source->ref)) {
                            return (string)$source->ref;
                        }
                        break;
                    case 'POST':
                        if (!empty($headers['id'])) {
                            return $headers['id'];
                        }

                        $property = 0;
                        $value = (int)$source->$property;
                        // this case the fileid
                        if ($value > 0) {
                            return $value;
                        }
                        break;
                    case 'PUT':
                    case 'DELETE':
                        return true;
                    default:
                        if (!empty($source->files->file)) {
                            $source = $source->files->file;
                            $isArray = true;
                        } elseif (!empty($source->notebooks->notebook)) {
                            $source = $source->notebooks->notebook;
                            $isArray = true;
                        } elseif (!empty($source->project->links)) {
                            $source = $source->project->links;
                            $isArray = true;
                        } else {
                            $attrs = $source->attributes();
                            $isArray = !empty($attrs->type) && (string)$attrs->type === 'array';
                        }
                        $this->headers = $headers;

                        $_this = self::toStdClass($source, $isArray);

                        foreach ($_this as $key => $value) {
                            $this->$key = $value;
                        }
                        return $this;
                }
            } else {
                if (!empty($source->error)) {
                    foreach ($source as $error) {
                        $errors .= $error . "\n";
                    }
                } else {
                    $property = 0;
                    $errors .= $source->$property;
                }
            }
        }
        throw new Exception([
            'Message' => $errors,
            'Response' => $data,
            'Headers' => $headers,
        ]);
    }

    /**
     *
     * @return string
     */
    protected function getContent(): string
    {
        $dom = new \DOMDocument('1.0');
        $dom->loadXML($this->string);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    /**
     *
     * @return string
     */
    public function getOriginalContent(): string
    {
        $dom = new \DOMDocument('1.0');
        $dom->loadXML($this->originalString);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    /**
     * Convierte un objecto SimpleXMLElement a stdClass
     *
     * @param \SimpleXMLElement $source
     * @param bool $isArray
     * @return \stdClass
     */
    private static function toStdClass(
        \SimpleXMLElement $source,
        $isArray = false
    ) {
        $destination = $isArray ? [] : new \stdClass();
        foreach ($source as $key => $value) {
            $key = Str::camel($key);
            $attrs = $value->attributes();
            if (!empty($attrs->type)) {
                $type = (string)$attrs->type;
                switch ($type) {
                    case 'integer':
                        $destination->$key = (int)$value;
                        break;
                    case 'boolean':
                        $value = (string)$value;
                        $destination->$key = (bool)$value === 'true';
                        break;
                    case 'array':
                        if (is_array($destination)) {
                            $destination[$key] = self::toStdClass($value, true);
                        } else {
                            $destination->$key = self::toStdClass($value, true);
                        }
                        break;
                    default:
                        $destination->$key = (string)$value;
                        break;
                }
            } else {
                $children = $value->children();
                if (!empty($children)) {
                    if ($isArray) {
                        $i = count($destination);
                        $destination[$i] = self::toStdClass($value);
                    } else {
                        $destination->$key = self::toStdClass($value);
                    }
                } else {
                    $destination->$key = (string)$value;
                }
            }
        }

        return $destination;
    }

    private function getXmlErrors($xml)
    {
        $errors = '';
        foreach (libxml_get_errors() as $error) {
            $errors .= $this->getXmlError($error, $xml) . "\n";
        }
        libxml_clear_errors();
        return $errors;
    }

    private function getXmlError(object $error, array $xml): string
    {
        $return = $xml[$error->line - 1] . "\n";
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
