<?php namespace TeamWorkPm\Request;

class XML extends Model
{
    private $doc;

    public function  __construct()
    {
        $this->doc               = new \DOMDocument();
        $this->doc->formatOutput = true;
    }

    protected function parseParameters($parameters)
    {
        if (!empty($parameters) && is_array($parameters)) {
            $wrapper = $this->doc->createElement($this->getWrapper());
            $parent = $this->doc->createElement($this->getParent());
            if ($this->actionInclude('/reorder')) {
                $parent->setAttribute('type', 'array');
                foreach ($parameters as $id) {
                    $element = $this->doc->createElement($this->parent);
                    $item = $this->doc->createElement('id');
                    $item->appendChild($this->doc->createTextNode($id));
                    $element->appendChild($item);
                    $parent->appendChild($element);
                }
            } else {
                foreach ($this->fields as $field=>$options) {
                    $value   = $this->getValue($field, $options, $parameters);
                    $element = $this->doc->createElement($field);
                    if (isset ($options['attributes'])) {
                        foreach ($options['attributes'] as $name=>$type) {
                            if (null !== $value) {
                                $element->setAttribute($name, $type);
                                if ($name == 'type') {
                                    if ($type == 'array') {
                                        $internal = $this->doc->createElement($options['element']);
                                        foreach ($value as $v) {
                                            $internal->appendChild($this->doc->createTextNode($v));
                                            $element->appendChild($internal);
                                        }
                                    } else {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if (null !== $value) {
                        if (is_bool($value)) {
                            $value = var_export($value, true);
                        }
                        $element->appendChild($this->doc->createTextNode($value));
                        !empty($options['sibling']) ?
                            $wrapper->appendChild($element) :
                            $parent->appendChild($element);
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

    protected function getWrapper()
    {
        return 'request';
    }
}