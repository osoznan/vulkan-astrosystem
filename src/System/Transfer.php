<?php
/**
 * Class for transfering data and serialization
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

class Transfer {

    protected static $_jsInserts;

    public static function serializeObjectVars($object, ...$varList) {
        $result = [];
        foreach ($varList as $item) {
            $value = $object->$item ?? null;
            if (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $result[$item] = $value->toArray();
                }
            } elseif (is_array($value)) {
                $result[$item] = static::serializeArray($object, $item)[$item];
            } else {
                $result[$item] = $value;
            }
        }
        return $result;
    }

    public static function serializeObjectVarsMinimized($object, ...$varList) {
        $result = [];
        foreach ($varList as $item) {
            $value = $object->$item;
            if (is_object($value)) {
                if (method_exists($value, 'toArrayMinimized')) {
                    $result[$item] = $value->toArrayMinimized();
                }
            } elseif (is_array($value)) {
                $result[$item] = static::serializeArrayMinimized($object, $item);
            } else {
                $result[$item] = $value;
            }
        }
        return $result;
    }

    public static function serializeArray($object, $arrayName) {
        $result = [];
        $value = $object->$arrayName;
        if (is_array($value) || is_object($value)) {
            $result[$arrayName] = [];
            foreach ($value as $key => $elem) {
                if (method_exists($elem, 'toArray')) {
                    $result[$arrayName][$key] = $elem->toArray();
                }
            }
        } else {
            $result[$arrayName] = $value;
        }
        return $result;
    }

    public static function serializeArrayMinimized($object, $array)
    {
        $newArr = [];
        $array = $object->$array;
        foreach ($array as $key => $elem) {
            $newArr[$key] = $elem->toArrayMinimized();
        }
        return $newArr;
    }

    public static function serializeArrayByProperty($array, $prop) {
        $arr = [];
        foreach ($array as $elem) {
            $arr[] = $elem->$prop;
        }
        return $array;
    }

}