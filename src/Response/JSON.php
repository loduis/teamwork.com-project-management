<?php

namespace TeamWorkPm\Response;

use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

/**
 * @template TKey of array-key
 * @template TValue
 * @extends Model<TKey,TValue>
 */
class JSON extends Model
{
    public function parse(string $data, array $headers): static | int | bool | null
    {
        /**
         * @var mixed
         */
        $source = json_decode($data);
        $errors = $this->getJsonErrors();
        $this->originalString = $this->string = $data;
        if ($errors === null) {
            if (!(
                $headers['Status'] === 201
                || $headers['Status'] === 200
                || $headers['Status'] === 204
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
            if (in_array($headers['Status'], [201, 200, 204])) {
                switch ($headers['Method']) {
                    case 'UPLOAD':
                        /**
                         * @var string|null
                         */
                        return $source->pendingFile->ref ??  null;
                    case 'POST':
                        if (!empty($headers['id']) && $headers['Status'] === 201) {
                            return (int)$headers['id'];
                        }

                        if (!empty($source->fileId)) {
                            return (int)$source->fileId;
                        }
                        /**
                         * @var  string
                         */
                        $wrapper = $headers['X-Parent'];
                        if (isset($source->$wrapper)) {
                            /**
                             * @var mixed
                             */
                            $source = $source->$wrapper;
                        }
                        // no break
                    case 'PUT':
                        return $source->id ?? true;
                    case 'DELETE':
                        return true;

                    default:
                        /**
                         * @var object
                         */
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
                        } elseif (strpos($headers['X-Action'], 'time_entries') !== false && $source !== null) {
                            $source = [];
                        }
                        $this->headers = $headers;
                        $this->string = json_encode($source);
                        if (is_arr_obj($source)) {
                            /**
                             * @var \stdClass
                             */
                            $data = static::camelizeObject($source);
                            if (!empty($data->id)) {
                                $data->id = (int)$data->id;
                            }
                            /** @psalm-suppress InvalidPropertyAssignmentValue  */
                            $this->data = $data;
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
    protected function getContent(): string
    {
        /**
         * @var object
         */
        $object = json_decode((string) $this->string);

        return json_encode($object, JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    public function getOriginalContent(): string
    {
        /**
         * @var object
         */
        $object = json_decode((string) $this->originalString);

        return json_encode($object, JSON_PRETTY_PRINT);
    }

    /**
     * @param mixed $source
     *
     * @return \ArrayObject|mixed
     */
    protected static function camelizeObject(mixed $source)
    {
        if (!is_arr_obj($source)) {
            return $source;
        }

        $destination = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS | \ArrayObject::STD_PROP_LIST);
        /**
         * @var string $key
         * @var mixed $value
         */
        foreach ($source as $key => $value) {
            if (ctype_upper($key)) {
                $key = strtolower($key);
            }
            $key = Str::camel($key);
            $destination->$key = is_scalar($value) ? $value : static::camelizeObject($value);
        }
        return $destination;
    }

    private function getJsonErrors(): ?string
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
