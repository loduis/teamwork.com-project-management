<?php

namespace TeamWorkPm\Response;

use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

class JSON extends Model
{
    /**
     * @param $data
     * @param array $headers
     *
     * @return $this
     * @throws \TeamWorkPm\Exception
     */
    public function parse($data, array $headers)
    {
        $source = json_decode($data);
        $errors = $this->getJsonErrors();
        $this->originalString = $this->string = $data;
        if (!$errors) {
            if (!(
                $headers['Status'] === 201
                || $headers['Status'] === 200
                || $headers['Status'] === 409
                || $headers['Status'] === 422
                || $headers['Status'] === 400
            )) {
                throw new Exception([
                    'Message' => $errors,
                    'Response' => $data,
                    'Headers' => $headers,
                ]);
            }
            if ($headers['Status'] === 201 || $headers['Status'] === 200) {
                switch ($headers['Method']) {
                    case 'UPLOAD':
                        return empty($source->pendingFile->ref) ? null : (string)$source->pendingFile->ref;
                    case 'POST':
                        if (!empty($headers['id'])) {
                            return (int)$headers['id'];
                        }

                        if (!empty($source->fileId)) {
                            return (int)$source->fileId;
                        }
                        // no break
                    case 'PUT':
                        return $source->id ?? true;
                    case 'DELETE':
                        return true;

                    default:
                        if (!empty($source->STATUS)) {
                            unset($source->STATUS);
                        }
                        if (!empty($source->project->files)) {
                            $source = $source->project->files;
                        } elseif (!empty($source->project->notebooks)) {
                            $source = $source->project->notebooks;
                        } elseif (!empty($source->project->links)) {
                            $source = $source->project->links;
                        } elseif (
                            !empty($source->messageReplies)
                            && preg_match('!messageReplies/(\d+)!', $headers['X-Action'])
                        ) {
                            $source = current($source->messageReplies);
                        } elseif (
                            !empty($source->people)
                            && preg_match('!projects/(\d+)/people/(\d+)!', $headers['X-Action'])
                        ) {
                            $source = current($source->people);
                        } elseif (
                            !empty($source->project)
                            && preg_match('!projects/(\d+)/notebooks!', $headers['X-Action'])
                        ) {
                            $source = [];
                        } elseif (
                            isset($source->cards)
                            && preg_match('!portfolio/columns/(\d+)/cards!', $headers['X-Action'])
                        ) {
                            $source = $source->cards;
                        } else {
                            $source = current($source);
                        }
                        if ($headers['X-Action'] === 'links' || $headers['X-Action'] === 'notebooks') {
                            $_source = [];
                            $wrapper = $headers['X-Action'];
                            foreach ($source as $project) {
                                foreach ($project->$wrapper as $object) {
                                    $_source[] = $object;
                                }
                            }
                            $source = $_source;
                        } elseif (strpos($headers['X-Action'], 'time_entries') !== false && !$source) {
                            $source = [];
                        }
                        $this->headers = $headers;
                        $this->string = json_encode($source);
                        $this->data = is_object($source) || is_array($source) ? self::camelizeObject($source) : $source;
                        if (!empty($this->data->id)) {
                            $this->data->id = (int)$this->data->id;
                        }

                        return $this;
                }
            } elseif (!empty($source->MESSAGE)) {
                $errors = $source->MESSAGE;
            } else {
                $errors = null;
            }
        }

        throw new Exception([
            'Message' => $errors,
            'Response' => $data,
            'Headers' => $headers,
        ]);
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $object = json_decode($this->string);

        return json_encode($object, JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    public function getOriginalContent()
    {
        $object = json_decode($this->originalString);

        return json_encode($object, JSON_PRETTY_PRINT);
    }

    /**
     * @param array|\stdClass $source
     *
     * @return \ArrayObject
     */
    protected static function camelizeObject($source)
    {
        if (!is_object($source) && !is_array($source)) {
            return $source;
        }

        $destination = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
        foreach ($source as $key => $value) {
            if (ctype_upper($key)) {
                $key = strtolower($key);
            }
            $key = Str::camel($key);
            $destination->$key = is_scalar($value) ? $value : self::camelizeObject($value);
        }
        return $destination;
    }

    /**
     * @codeCoverageIgnore
     */
    private function getJsonErrors()
    {
        $errorCode = json_last_error();
        if (!$errorCode) {
            return null;
        }

        if (function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }

        switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
        }
        return null;
    }
}
