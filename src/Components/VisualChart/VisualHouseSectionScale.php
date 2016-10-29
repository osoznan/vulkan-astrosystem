<?php
/**
 * Scale for displaying cusps for a section
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\Components\VisualChart;

use vulkan\Core\HouseScale;
use vulkan\System\Config;
use vulkan\System\Vulkan;

class VisualHouseSectionScale extends HouseScale
{

    use SectionScaleVisualEngine;
    use CommonVisualParamsTrait;

    const DEFAULT_CSS_CLASS = 'vul-scale';

    public $stickOutDistance = 5;

    public $pointFontSize = 15;

    function __construct() {
        $this->cssClass('vul-house-scale');
      //  $this->attachedSection($this->section);
        $this->clip(0, 1);
    }

    protected function drawGeneralScaleLines() {
        $this->chart->canvas->beginGroup('vul-scale-divisions');
        foreach($this->section->houses as $house) {
            $c1 = $this->chart->radiusLine($this->_lineClipInnerRadius, $this->_lineClipOuterRadius, $house->position);
            $this->chart->canvas->line($c1[0], $c1[1], $c1[2], $c1[3]);
        }
        $this->chart->canvas->endGroup();
    }

}