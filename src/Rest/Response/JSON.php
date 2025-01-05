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
        $source = json_decode($data, true);
        $errors = $this->getJsonErrors();
        $this->originalString = $this->string = $data;

        [
            'Status' => $status,
            'X-Action' => $action,
            'X-Parent' => $wrapper,
            'Method' => $method
        ] = $headers;

        if (!in_array($status, [200, 201, 204])) {
            $errors = $source['MESSAGE'] ?? $source['error'] ?? $errors ?? "Unknown error ($status) status";
        }
        if ($errors !== null) {
            throw new Exception([
                'Message' => $errors,
                'Response' => $data,
                'Headers' => $headers,
            ]);
        }

        switch ($method) {
            case 'POST':
                if (!empty($headers['id']) && $status === 201) {
                    return (int) $headers['id'];
                }

                if (!empty($source['fileId'])) {
                    return (int) $source['fileId'];
                }

                if (!empty($source['pendingFile']['ref'])) {
                    return $source['pendingFile']['ref'];
                }

                if (isset($source[$wrapper])) {
                    /**
                     * @var mixed
                     */
                    $source = $source[$wrapper];
                }
                // no break
            case 'PUT':
                unset($source['STATUS']);
                $keys = array_keys($source);
                if ($headers['X-Not-Use-Files'] &&
                    ($count = count($keys)) && (
                        $count != 1 || !in_array('id', $keys)
                    )
                ) {
                    if (
                        preg_match('!/(\d+)/tags!', $action) && !empty($source['tags'])
                    ) {
                        return true;
                    }
                    /**
                     * @var \stdClass
                     */
                    $data = static::camelizeObject($source);
                    if (!empty($data->id)) {
                        $data->id = (int) $data->id;
                    }
                    /** @psalm-suppress InvalidPropertyAssignmentValue  */
                    $this->data = $data;
                    return $this;
                }
                $id = $source['id'] ?? null;
                if ($id !== null) {
                    $id = (int) $id;
                }
                return $id ?? true;
            case 'DELETE':
                return true;

            default:
                /**
                 * @var array
                 */
                if (!empty($source['STATUS'])) {
                    unset($source['STATUS']);
                }

                $count = count($source);
                if ($count == 1) {
                    $key = key($source);
                    $match = $wrapper == $key;
                    $source = $match ? $source[$wrapper] : current($source);
                    if ($source) {
                        if (preg_match('!projects/(\d+)/time/total!', $action)) {
                            $source = current($source);
                        } elseif (
                            preg_match('!messageReplies/(\d+)!', $action)
                        ) {
                            $source = current($source);
                        } elseif (preg_match('!tasklists/(\d+)/time/total!', $action)) {
                            $source = current($source);
                            $source = $source['tasklist'];
                        } elseif (preg_match('!tasks/(\d+)/time/total!', $action)) {
                            $source = current($source);
                            $source = $source['tasklist']['task'];
                        }
                    }

                    if ($key === 'project') {
                        foreach(['files'] as $key) {
                            if (isset($source[$key])) {
                                $source = $source[$key];
                                break;
                            }
                        }
                    } elseif ($key === 'projects' && $action === 'files') {
                        $data = [];
                        foreach($source as $project) {
                            foreach ($project['files'] as $file) {
                                $data[] = $file;
                            }
                        }
                        $source = $data;
                    }
                } elseif (
                    isset($source['card'])
                    && preg_match('!portfolio/cards/(\d+)!', $action)
                ) {
                    $source = $source['card'];
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
            if (ctype_upper((string) $key)) {
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
