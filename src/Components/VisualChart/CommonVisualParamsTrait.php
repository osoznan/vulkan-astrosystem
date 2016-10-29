<?php
/**
 * Trait for all visual chart objects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Components\VisualChart;

use vulkan\Core\C;

trait CommonVisualParamsTrait {

    /* @var $class string a css-class name for an element */
    public $cssClass;

    public function cssClass($class) {
        $this->cssClass = $class;
        return $this;
    }

}