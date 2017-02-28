<?php
/**
 * Trait for visual display of different classes which belong to scales
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\VisualChart;

use vulkan\System\C;

trait SectionScaleVisualEngine {

    /* @var $canvas \vulkan\Core\Svg\Svg */
    public $canvas;

    public $active = true;

    protected $_majorStep = null;

    public $lineClipInnerSize;
    public $lineClipOuterSize;
    protected $_lineClipInnerRadius;
    protected $_lineClipOuterRadius;

    public function sizeChanged() {
        $this->_lineClipInnerRadius = $this->section->innerRadius + $this->lineClipInnerSize * $this->section->width;
        $this->_lineClipOuterRadius = $this->section->innerRadius + $this->lineClipOuterSize * $this->section->width;
    }

    public function clip($params) {
        $this->lineClipInnerSize = $params[0];
        $this->lineClipOuterSize = $params[1];

        return $this;
    }

    public function majorStep($step = null) {
        if ($step) {
            $this->_majorStep = $step;
        } else {
            return $this->_majorStep;
        }
        return $this;
    }

    protected function drawBasic() {
        $this->chart->canvas->circleEmpty($this->chart->centerX, $this->chart->centerY, $this->_lineClipInnerRadius, 'vul-scale-inner-circle');
        $this->chart->canvas->circleEmpty($this->chart->centerX, $this->chart->centerY, $this->_lineClipOuterRadius, 'vul-scale-outer-circle');
        $this->chart->ring($this->_lineClipInnerRadius, $this->_lineClipOuterRadius, 'vul-scale-ring');
    }

    protected function drawGeneralScaleLines() {
        $this->chart->canvas->beginGroup('vul-scale-divisions');
        foreach($this->positions as $idx => $position) {
            if (($this->_majorStep && ($idx % $this->_majorStep != 0)) || !$this->_majorStep) {
                $c = $this->chart->radiusLine($this->_lineClipInnerRadius, $this->_lineClipOuterRadius, $position);
                $this->chart->canvas->line($c[0], $c[1], $c[2], $c[3]);
            }
        }
        $this->chart->canvas->endGroup();
    }

    protected function drawMajorScaleLines() {
        if (!$this->_majorStep) {
            return;
        }
        $this->chart->canvas->beginGroup('vul-scale-major-divisions');
        foreach($this->positions as $idx => $position) {
            if ($idx % $this->_majorStep == 0) {
                $c = $this->chart->radiusLine($this->_lineClipInnerRadius, $this->_lineClipOuterRadius, $position);
                $this->chart->canvas->line($c[0], $c[1], $c[2], $c[3]);
            }
        }
        $this->chart->canvas->endGroup();
    }

    protected function drawScaleLines() {
        $this->drawGeneralScaleLines();
        $this->drawMajorScaleLines();
    }

    /**
     * Draw text in the center of a sector
     * @param $sectorId
     * @param $text
     */
    public function drawCaption($sectorId, $text) {
        $curr = $this->positions[$sectorId];
        $next = $this->positions[$this->nextSectorIndex($sectorId)];
        C::normalizeByAscRad($curr, $next);
        $this->chart->drawCaption($this->section, ($curr + $next) / 2, $text);
    }

    public function drawCaptions() { }

    protected function drawAll() {
        $this->drawBasic();
        $this->drawScaleLines();
        $this->drawCaptions();
    }

    public function draw() {
        if ($this->active) {
            $this->chart->canvas->beginGroup([self::DEFAULT_CSS_CLASS, $this->cssClass],
                isset($this->key) ? 'id="' . $this->key . '"' : null);
            $this->drawAll();
            $this->chart->canvas->endGroup();
        }
    }
}