<?php
/**
 * Class for a widget which is inserted into Vulkan AstroSystem view.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

use  vulkan\Helpers\Strings;
use vulkan\System\Config;
use vulkan\System\Vulkan;

class Widget {

    public $params;

    public static $onPrint;

    public static function getRegisteredAjaxClasses() {
        return [];
    }

    public function __construct($params = null) {
        Config::settings($this, $params);
    }

    public function run() {}

    public function renderFile($file) {
        ob_start();
        ob_implicit_flush(false);
        require($file);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function render($name = null)
    {
        if (file_exists($file = $this->getViewPath() . $name . '.php')) {
            $str = $this->renderFile($file);
        } elseif (file_exists($file = $this->getViewPath() . '/../_GlobalTemplates/' . $name . '.php')) {
            $str = $this->renderFile($file);
        } else {
            $str = $name;
        }
        return '<div class="vul-component">' . $str . '</div>';
    }

    public function print($text, $params = null) {
        $ev = call_user_func(static::$onPrint ?? function() {}, ['text' => &$text, 'params' => $params]);
        return $ev ? $ev->text : $text;
    }

    public function getViewPath() {
        return Vulkan::getConfig('dir.frontend') . 'Components' . DIR_SLASH . Strings::shortClassName(get_class($this)) . DIR_SLASH;;
    }

}