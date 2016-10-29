<?php
/**
 * Configuration management of object methods and properties
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

class Config {

    protected static $parents=[];
    public static $parent, $count = 0;

    public static function settings($object, $params) {
        if ($params) {
            foreach ($params as $key => $data) {
                if (is_array($data) or method_exists($object, $key)) {
                    if (method_exists($object, $key)) {
                        $object->$key($data);
                    } elseif (isset($data['class'])) {
                        //if ($key == 'aspectManager') die();
                        $newObject = new $data['class']();
                        self:$parents[] = $newObject;
                        self::settings($newObject, $data);

                        self::$parent = array_pop(self::$parents);

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