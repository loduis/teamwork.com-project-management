<?php namespace TeamWorkPm\Response;

use \TeamWorkPm\Helper\Str;

class JSON extends Model
{

    public function parse($data, array $headers)
    {
        $source = json_decode($data);
        $errors = $this->getJsonErrors();
        $this->string = $data;
        if (!$errors) {
            if ($headers['Status'] === 201 || $headers['Status'] === 200) {
                switch($headers['Method']) {
                    case 'UPLOAD':
                        return empty($source->pendingFile->ref) ? null :
                                            (string) $source->pendingFile->ref;
                    case 'POST':
                        if (!empty($headers['id'])) {
                            return (int) $headers['id'];
                        } elseif (!empty($source->fileId)) {
                            return (int) $source->fileId;
                        }
                        // no break
                     case 'PUT':
                     case 'DELETE':
                         return true;
                     default:
                        if (!empty($source->STATUS)) {
                            unset($source->STATUS);
                        }
                        if (!empty($source->project->files)) {
                            $source = $source->project->files;
                        } elseif(!empty($source->project->notebooks)) {
                            $source = $source->project->notebooks;
                        } elseif(!empty($source->project->links)) {
                            $source = $source->project->links;
                        } elseif (!empty($source->messageReplies) &&
                            ($match = preg_match(
                                '!messageReplies/(\d+)!',
                                $headers['X-Action']))) {
                                $source = current($source->messageReplies);
                        } elseif (!empty($source->people) &&
                            preg_match(
                                '!projects/(\d+)/people/(\d+)!',
                                $headers['X-Action'])) {
                            $source = current($source->people);
                        } else {
                            $source = current($source);
                        }
                        if ($headers['X-Action'] === 'links' ||
                                        $headers['X-Action'] === 'notebooks') {
                            $_source = [];
                            $wrapper = $headers['X-Action'];
                            foreach ($source as $project) {
                                foreach ($project->$wrapper as $object) {
                                    $_source[] = $object;
                                }
                            }
                            $source = $_source;
                        } elseif (
                            strpos($headers['X-Action'], 'time_entries') > 0 &&
                            !$source
                        ) {

                            $source = [];
                        }
                        $this->headers = $headers;
                        $this->string = json_encode($source);
                        $_this = self::camelizeObject($source);
                        foreach ($_this as $key=>$value) {
                            $this->$key = $value;
                        }
                        if (!empty($this->id)) {
                            $this->id = (int) $this->id;
                        }
                        return $this;
                }
            } elseif (!empty($source->MESSAGE)) {
                $errors = $source->MESSAGE;
            } else {
                $errors = null;
            }
        }

        throw new \TeamWorkPm\Exception([
            'Message'  => $errors,
            'Response' => $data,
            'Headers'  => $headers
        ]);
    }

    protected function getContent()
    {
        $object = json_decode($this->string);

        return json_encode($object, JSON_PRETTY_PRINT);
    }

    protected static function camelizeObject($source)
    {
        $destination = new \stdClass();
        foreach ($source as $key=>$value) {
            if (ctype_upper($key)) {
                $key = strtolower($key);
            }
            $key = Str::camel($key);
            $destination->$key = is_scalar($value) ?
                                        $value : self::camelizeObject($value);
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
            return;
        }

        if (function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }

        switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        }
    }
}
