<?php

namespace TeamWorkPm;

class Factory
{
    /**
     * @param string $class_name
     * @return Model
     */
    public static function build($class_name)
    {
        $class_name = str_replace(['/', '.'], '\\', $class_name);
        $class_name = preg_replace_callback('/(\\\.)/', static function ($matches) {
            return strtoupper($matches[1]);
        }, $class_name);
        $class_name = ucfirst($class_name);

        // Special treatment of Task List
        if (strcasecmp($class_name, 'task\\list') === 0) {
            $class_name = 'Task_List';
        }
        $class_name = '\\' . __NAMESPACE__ . '\\' . $class_name;

        return forward_static_call_array([$class_name, 'getInstance'], Auth::get());
    }
}
