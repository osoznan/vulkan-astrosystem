<?php
/**
 * Chart Widget
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Components;

use vulkan\Components\VisualChart\VisualEqualSectionScale;

class VisualChart extends \vulkan\System\Widget {

    public $chart;

    public function run() {
        return $this->chart->run($this->width, $this->height);
    }
}