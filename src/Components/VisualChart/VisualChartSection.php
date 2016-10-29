<?php
/**
 * Class for a section display
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Components\VisualChart;

use vulkan\Core\{
    ChartSection, SectionScale, C
};
use vulkan\Core\AstroPoint;
use vulkan\Core\InfoObjects\AstroPointInfo;
use vulkan\Core\InfoObjects\InfoObject;
use vulkan\System\Config;
use yii\helpers\VarDumper;
use vulkan\System\Transfer;


class VisualChartSection extends ChartSection
{

    use CommonVisualParamsTrait;

    const DEFAULT_CSS_CLASS = 'vul-section';

    /* @var $_chart \vulkan\Core\Chart */
    public $chart;

    public $info;

    public $visible = true;

    public $width;

    public $radius;
    public $innerRadius;

    public $pointFontSize = 25;
    public $showPointInfo = true;

    public function __construct($params = null) {
        parent::__construct($params);
    }

    public function adjustScales() {
        foreach ($this->scales as $scale) {
            $scale->sizeChanged();
        }
    }

    public function radius($radius) {
        $this->radius = round($radius);
        $this->innerRadius = $this->radius - $this->width;
        $this->adjustScales();
        return $this;
    }

    public function width($width) {
        $this->width = round($width);
        $this->innerRadius = $this->radius - $this->width;
        $this->adjustScales();
        return $this;
    }

    public function drawBasic() {
        $this->chart->ring($this->innerRadius, $this->innerRadius + $this->width, 'vul-section-ring');
    }

    public function drawScales() {
        $this->chart->canvas->beginGroup('vul-section-scales');
        foreach ($this->scales as $scale) {
            if ($scale->visible) {
                $scale->draw();
            }
        }
        $this->chart->canvas->endGroup();
    }

    public function drawPoints() {
        $this->chart->canvas->beginGroup('vul-section-points');
        foreach ($this->points as $point) {
            if ($point->visible) {
                $point->draw($this);
            }
        }
        $this->chart->canvas->endGroup();
    }

    public function avoidPointsOverlap() {

        $points = [];
        foreach ($this->points as $point) {
            if ($point->visible) {
                $points[] = $point;
                $point->outputAngle = $point->position;
            }
        }

        if (count($points) < 2) {
            return;
        }

        usort($points, function($p1, $p2) {
            return $p1->position > $p2->position ? 1 : -1;
        });

        list($delta, $maxLevel) = [
            $this->pointFontSize / (2 * $this->innerRadius) * 2,
            floor($this->width / $this->pointFontSize * 0.5)
        ];
        $lvl = 0;
        $curr = reset($points);
        $lastZeroLevelPoint = $curr;
        while ($next = next($points)) {

            if (modPi($curr->distanceFrom($next)) < $delta) {

                if (abs($curr->position - $lastZeroLevelPoint->position) <= $delta || $lvl == 0) {
                    $lvl = $lvl < $maxLevel ? $lvl + 1 : 0;
                } else {
                    $lvl = 0;
                }

                $next->radiusLevel = $lvl;
            } else {
                $next->radiusLevel = 0;
                $lvl = 0;
            }

            if ($lvl == 0) {
                $lastZeroLevelPoint = $curr;
            }

            $curr = $next;
        }
    }

    public function pointFontSize($value) {
        $this->pointFontSize = $value;
        return $this;
    }

    public function showPointInfo($value) {
        $this->showPointInfo = $value;
        return $this;
    }

    public function draw() {
        if ($this->visible) {
            $this->chart->canvas->beginGroup([self::DEFAULT_CSS_CLASS . ' ' . ($this->cssClass ?? $this->key)]);
            $this->drawBasic();
            $this->drawScales();
            $this->avoidPointsOverlap();
            $this->drawPoints();
            $this->chart->canvas->endGroup();
        }
    }

    public function points($pointIds) {
        $points = [];
        foreach ($pointIds as $id) {
            $points[] = new VisualAstroPoint(\vulkan\Core\C::$pointInfos[$id]);
        }
        $this->addPoints($points);
        return $this;
    }

    public function houses($data) {
        $this->addHouses(\vulkan\Core\ChartSection::createAllHouses());
        return $this;
    }

    public function scales($scales) {
        foreach ($scales as $scale) {
            $scaleObject = new $scale['class']();
            Config::settings($scaleObject, $scale);
            $this->addScale($scaleObject);
        }
        return $this;
    }

    public function toArray() {
        return array_merge(
            parent::toArray(),
            Transfer::serializeObjectVars($this,
                'width', 'radius', 'innerRadius', 'pointFontSize', 'showPointInfo')
        );
    }
}