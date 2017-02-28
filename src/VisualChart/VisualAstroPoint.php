<?php
/**
 * Class for point display
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\VisualChart;

use vulkan\Core\AstroPoint;
use vulkan\System\C;

class VisualAstroPoint extends AstroPoint
{

    use CommonVisualParamsTrait;

    const DEFAULT_CSS_CLASS = 'vul-point';
    const MIN_POINT_ANGLE_DIST = 0.31;

    public $chart;

    public $outputAngle;

    public $visible = true;

    public $radiusLevel = 0;

    public function drawCaption($position) {
        $fontSize = $this->section->pointFontSize;

        $coord = $this->chart->radiusPoint($this->section->innerRadius
            + $fontSize * ($this->radiusLevel + 0.75), $position);

        $smallFontSize = $fontSize / 2;
        $showInfo = $smallFontSize < 9 ? false : true;

        $this->chart->canvas->addCode(
            '<text x="' . $coord[0] . '" y="' . ($coord[1] + $fontSize / 3) . '">'
                    . '<tspan class="vul-point-caption" font-size="' . $fontSize . '">' . $this->info->caption . '</tspan>' .
                '<tspan dy="0" class="vul-point-retrograde">' . ($this->isRetro() ? 'R' : '') . '</tspan>' .
                (($this->section->showPointInfo && $showInfo ) ? ('<tspan dx="' . ($this->isRetro() ? '-6' : '') . '" dy="' . (-$fontSize / 3) . '" font-size="' . $smallFontSize   . '" class="vul-point-info-caption">'
                    . (floor(C::radDeg($position) % 30))) : '') . '</tspan></text>');
    }

    public function draw() {
        $this->chart->canvas->beginGroup(
            [self::DEFAULT_CSS_CLASS, $this->cssClass . ($this->selected ? ' vul-point-selected' : '')], 'data-id="' . $this->info->id . '"');

        $coord = $this->chart->radiusPoint($this->section->innerRadius, $this->position);
        $this->drawCaption($this->outputAngle);
        $this->chart->canvas->circle($coord[0], $coord[1], 2, 'vul-point-pos');
        $coord = $this->chart->radiusPoint($this->chart->innerRadius, $this->position);
        $this->chart->canvas->circle($coord[0], $coord[1], 2, 'vul-point-pos-inner');

        $this->chart->canvas->endGroup();
    }

}