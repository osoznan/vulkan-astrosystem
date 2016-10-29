<?php
/**
 * Section for displaying cusps ("global houses") of a chart
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

class GlobalHouseSectionScale extends HouseScale
{

    use SectionScaleVisualEngine;
    use CommonVisualParamsTrait;

    const DEFAULT_CSS_CLASS = 'vul-scale';

    public $stickOutDistance = 5;

    public $pointFontSize = 15;

    function __construct() {
        $this->cssClass('vul-global-house-scale');
        $this->clip(0, 1);
    }

    public function stickOutDistance($value) {
        $this->stickOutDistance = $value;
        return $this;
    }

    protected function drawGeneralScaleLines() {
        $this->chart->canvas->beginGroup('vul-scale-divisions');
        foreach($this->attachedSection->houses as $house) {
            $c1 = $this->chart->radiusLine($this->chart->innerRadius, $this->_lineClipOuterRadius + $this->stickOutDistance, $house->position);
            $this->chart->canvas->line($c1[0], $c1[1], $c1[2], $c1[3]);
        }
        $this->chart->canvas->endGroup();
    }

    public function drawCaption($house) {
        $this->chart->canvas->beginGroup('vul-point');

        $coord = $this->chart->radiusPoint($this->_lineClipOuterRadius + $this->stickOutDistance + $this->section->pointFontSize / 3,
            $house->position);
        $this->chart->canvas->text($coord[0], $coord[1], $house->info->caption, 'vul-point-caption',  'font-size="' . $this->pointFontSize . '"');
        $this->chart->canvas->text($coord[0], $coord[1] + $this->section->pointFontSize / 3,
            floor(radDeg(modRad($house->position)) % 30), 'vul-point-info-caption');

        $this->chart->canvas->endGroup();
    }

    public function drawCaptions() {
        $this->chart->canvas->beginGroup('vul-scale-captions');
        if ($this->attachedSection) {
            foreach ($this->attachedSection->houses as $house) {
                $this->drawCaption($house);
            }
        }
        $this->chart->canvas->endGroup();
    }

}