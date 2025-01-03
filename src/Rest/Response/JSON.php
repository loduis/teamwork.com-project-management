<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Response;

use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

/**
 * @template TKey of array-key
 * @template TValue
 * @extends Model<TKey,TValue>
 */
class JSON extends Model
{
    public function parse(string $data, array $headers): static | int | bool | string | null
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
                || $headers['Status'] === 404
            )) {
                throw new Exception([
                    'Message' => $errors,
                    'Response' => $data,
                    'Headers' => $headers,
                ]);
            }
            if (in_array($headers['Status'], [201, 200, 204])) {
                switch ($headers['Method']) {
                    case 'POST':
                        if (!empty($headers['id']) && $headers['Status'] === 201) {
                            return (int)$headers['id'];
                        }

                        if (!empty($source->fileId)) {
                            return (int)$source->fileId;
                        }
                        if (!empty($source->pendingFile->ref)) {
                            return $source->pendingFile->ref;
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
                        unset($source->STATUS);
                        $keys = get_object_vars($source);
                        if ($headers['X-Not-Use-Files'] &&
                            ($count = count($keys)) && (
                                $count != 1 || !isset($keys['id'])
                            )
                        ) {
                            if (
                                preg_match('!/(\d+)/tags!', $headers['X-Action']) && !$source->tags
                            ) {
                                return true;
                            }
                            /**
                             * @var \stdClass
                             */
                            $data = static::camelizeObject($source);
                            if (!empty($data->id)) {
                                $data->id = (int)$data->id;
                            }
                            /** @psalm-suppress InvalidPropertyAssignmentValue  */
                            $this->data = $data;
                            return $this;
                        }
                        if (!empty($source->id)) {
                            $source->id = (int) $source->id;
                        }
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
                        $wrapper = $headers['X-Parent'];
                        $action = $headers['X-Action'];
                        $count = count(get_object_vars($source));
                        if ($count == 1) {
                            $key = key($source);
                            $match = $wrapper == $key;
                            $source = $match ? $source->$wrapper : current($source);
                            // projects/967489/time/total
                            if (
                                $source
                                && preg_match('!projects/(\d+)/time/total!', $headers['X-Action'])
                            ) {
                                $source = current($source);
                            }
                            // tasklists/2952529/time/total
                            if ($source && preg_match('!tasklists/(\d+)/time/total!', $headers['X-Action'])) {
                               $source = current($source);
                               $source = $source->tasklist;
                            }
                            // tasks/43119773/time/total
                            if ($source && preg_match('!tasks/(\d+)/time/total!', $headers['X-Action'])) {
                                $source = current($source);
                                $source = $source->tasklist->task;
                            }
                            if ($key === 'project') {
                                foreach(['files'] as $key) {
                                    if (isset($source->$key)) {
                                        $source = $source->$key;
                                        break;
                                    }
                                }
                            } elseif ($key === 'projects' && $action === 'files') {
                                $data = [];
                                foreach($source as $project) {
                                    foreach ($project->files as $file) {
                                        $data[] = $file;
                                    }
                                }
                                $source = $data;
                            }
                        }
                        /*
                        if (isset($source->project->files)) {
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
                        } elseif (isset($source->$parent)) {
                            $source = $source->$parent;
                        } else {
                            echo $parent;
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
                        } elseif (str_contains($headers['X-Action'], 'time_entries') && $source !== null) {
                            $source = [];
                        }
                        */

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
            }  elseif (!empty($source->error)) {
                $errors = $source->error;
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

        $destination = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
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

        return json_last_error_msg();
    }
}
