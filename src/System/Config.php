<?php
/**
 * Configuration management of object methods and properties
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

class Config {

    public static function settings($object, $params) {
        if ($params) {
            foreach ($params as $key => $data) {
                if (is_array($data) or method_exists($object, $key)) {
                    if (method_exists($object, $key)) {
                        $object->$key($data);
                    } elseif (isset($data['class'])) {
                        $newObject = new $data['class']();
                        $newObject->parent = $object;
                        self::settings($newObject, $data);
                        $object->$key = $newObject;
                        unset($data['class']);
                    } else {
                        $object->{$key} = $data;
                    }
                } else {
                    $object->{$key} = $data;
                }
            }
        }
    }

    public static function copyProperties($source, &$dest) {
        $refl = new \ReflectionClass($source);
        foreach ($refl->getProperties(\ReflectionProperty::IS_PUBLIC || \ReflectionProperty::IS_STATIC) as $prop) {
            echo $name = $prop->getName();
            $dest->$name = $source->$name;
        }
    }


}