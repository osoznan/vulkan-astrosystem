<?php
/**
 * A global entry point of the basic Vulkan AstroSystem elements (further them will be more)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\System;

require(__DIR__ . '/AstroCalc.php');

class Vulkan {

    const VERSION = '0.1b';

    public static $jsRootUrl = '/js/vulkan/';
    public static $cssRootUrl = '/css/vulkan/';
    public static $imageRootUrl = '/images/vulkan';

    /** @var $app \vulkan\Core\Application */
    public static $app;

    public static function getConfig($selector) {
        $keys = explode('.', $selector);
        $config = static::$app->config;
        foreach ($keys as $key) {
            $config = $config[$key];
        }
        return $config;
    }

    public static function getInstance($classAlias) {
        if (stripos($classAlias, '.')) {
            $class = static::getConfig($classAlias);
            return new $class;
        } elseif (is_string($classAlias)) {
            return new $classAlias;
        }
    }

    /** native "var_dump" for Vulkan. Calls die() after dump by default */
    public static function d($var, $isStop = true) {
        if (is_object($var)) {
            var_dump($var);
        } elseif (is_array($var)) {
            print_r(implode('<P>', $var));
        } else {
            echo $var ?? 'null...';
        }
        echo '<br>';
        if ($isStop) {
            die();
        }
    }

}