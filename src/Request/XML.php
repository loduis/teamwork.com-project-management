<?php

namespace TeamWorkPm\Request;

use DOMDocument;

class XML extends Model
{
    /**
     * @var DOMDocument
     */
    private DOMDocument $doc;

    public function __construct()
    {
        $this->doc = new DOMDocument();
        $this->doc->formatOutput = true;
    }

    protected function parseParameters(array $parameters): ?string
    {
        if (!empty($parameters)) {
            $wrapper = $this->doc->createElement($this->getWrapper());
            $parent = $this->doc->createElement($this->getParent());
            if ($this->actionInclude('/reorder')) {
                $parent->setAttribute('type', 'array');
                foreach ($parameters as $id) {
                    $element = $this->doc->createElement((string) $this->parent);
                    $item = $this->doc->createElement('id');
                    $item->appendChild($this->doc->createTextNode($id));
                    $element->appendChild($item);
                    $parent->appendChild($element);
                }
            } else {
                $noUpdate = $this->method !== 'PUT';
                foreach ($this->fields as $field => $options) {
                    $value = $this->getValue($field, $options, $parameters);
                    if ($value !== null && $noUpdate &&
                        ($options['on_update'] ?? false) !== false
                    ) {
                        continue;
                    }
                    $element = $this->doc->createElement($field);
                    if (isset($options['attributes'])) {
                        foreach ($options['attributes'] as $name => $type) {
                            if ($value !== null) {
                                $element->setAttribute($name, $type);
                                if ($name == 'type') {
                                    if ($type == 'array') {
                                        $internal = $this->doc->createElement($options['element']);
                                        foreach ($value as $v) {
                                            $internal->appendChild($this->doc->createTextNode($v));
                                            $element->appendChild($internal);
                                        }
                                    } elseif (!in_array($type, ['email'])) {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if ($value !== null) {
                        if (is_bool($value)) {
                            $value = var_export($value, true);
                        }
                        $element->appendChild($this->doc->createTextNode($value));
                        !empty($options['sibling'])
                            ? $wrapper->appendChild($element)
                            : $parent->appendChild($element);
                    }
                }
            }
            $wrapper->appendChild($parent);
            $this->doc->appendChild($wrapper);

            $parameters = $this->doc->saveXML();
        } else {
            $parameters = null;
        }

        return $parameters;
    }

    protected function getWrapper(): string
    {
        return 'request';
    }
}
