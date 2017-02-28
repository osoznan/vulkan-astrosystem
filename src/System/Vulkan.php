<?php
/**
 * A global entry point of the basic Vulkan AstroSystem elements (further them will be more)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\System;

class Vulkan {

    const VERSION = '0.1b';

    /** @var string Path to your web project's javascripts */
    public static $jsRootUrl = '/vulkan/js/';
    /** @var string Path to your web project's stylesheets */
    public static $cssRootUrl = '/vulkan/css/';
    /** @var string Path to your web project's images */
    public static $imageRootUrl = '/vulkan/images/';

    /** @var array Configuration data */
    public static $config;

    public static $ephemerisAdapter;

    /**
     * Initialization of Vulkan AstroSystem
     * @param null $config
     * @throws \Exception
     */
    public static function initialize($config = null) {
        static::$config = require(__DIR__ . '/../../front/Config/main.php');
        $ephAdapt = self::getConfig('ephemerisAdapter.class');
        static::$ephemerisAdapter = new $ephAdapt;
    }

    /**
     * Gets value from the Vulkan config file
     * @param string $selector "Path" to a config parameter, i.e. 'dir.base' gets Vulkan::$app->config['dir']['base']
     * @return array
     */
    public static function getConfig($selector) {
        $keys = explode('.', $selector);
        $config = static::$config;
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                throw new \Exception('wrong config selector');
            }
        }
        return $config;
    }

    public static function config($value) {
        static::$config = $value;
    }

    public static function getInstance($classAlias) {
        if (stripos($classAlias, '.')) {
            $class = static::getConfig($classAlias);
            return new $class;
        } elseif (is_string($classAlias)) {
            return new $classAlias;
        }
    }

    public static function ephemerisAdapter($value) {
        static::$ephemerisAdapter = $value;
    }

    /**
     * Native "var_dump" for Vulkan. Calls die() after dump by default
     * @param $var A variable to dump
     * @param bool|true $isStop is die() after dump
     */
    public static function d($var, $isStop = true) {
        if (is_object($var)) {
            var_dump($var);
        } elseif (is_array($var)) {
            print_r(implode('<p>', $var));
        } else {
            echo isset($var) ? $var : 'null...';
        }
        echo '<br>';
        if ($isStop) {
            die();
        }
    }

    public static function insertCss($fileName) {
        echo '<style>', file_get_contents(static::$config['dir']['front'] . 'Web/css/' . $fileName), '</style>';
    }

}