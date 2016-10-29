<?php
/**
 * A view
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

use vulkan\System\Vulkan;

class View {

    protected $_places = [];
    protected $_frame;

    protected $_jsFiles = [];
    protected $_cssFiles = [];

    private $_js;
    private $_css;

    public function render($fileName, $params = []) {
        $str = $this->_placeCssFiles() . $this->_placeJsFiles();
        return '?>' . file_get_contents(Vulkan::$app->controller->getViewPath() . $fileName . '.php');
    }

    public function assign($key, $value) {
        $this->$key = $value;
        return $this;
    }

    public function frame($value) {
        $this->_frame = $value;
        return $this->_frame;
    }

    public function addJsCode($value) {
        $this->_js .= '<script>' . $value . '</script>';
    }

    public function addJsFile($fileName) {
        if (!isset($this->_jsFiles[$fileName])) {
            $this->_jsFiles[$fileName] = '<script src="' . Vulkan::$jsRootUrl . $fileName . '"></script>';
        }
    }

    protected function _placeCssFiles() {
        return implode("\n", $this->_cssFiles);
    }

    protected function _placeJsFiles() {
        return implode("\n", $this->_jsFiles);
    }

    public function addCssCode($value) {
        $this->_css .= '<style>' . $value . '</style>';
    }

    public function addCssFile($fileName) {
        if (!isset($this->_cssFiles[$fileName])) {
            $this->_cssFiles[$fileName] = '<link rel="stylesheet" type="text/css" href="' . Vulkan::$cssRootUrl . $fileName . '">';
        }
    }

    public function content() {
        return eval($this->_content);
    }

    public function renderAll($content) {
        $this->_content = $content;
        require(Vulkan::$app->controller->getViewPath() . (Vulkan::$app->controller->frame ?? Vulkan::$app->frame ?? 'frame_default') . '.php');
    }
}