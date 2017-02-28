<?php
/**
 * Trait for all visual chart objects
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\VisualChart;

use vulkan\System\C;

trait CommonVisualParamsTrait {

    /* @var string A css-class name for an element */
    public $cssClass;

    public function cssClass($class) {
        $this->cssClass = $class;
        return $this;
    }

}