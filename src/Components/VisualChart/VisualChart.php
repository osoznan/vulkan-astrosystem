<?php
/**
 * Class for chart display, the entry point of gathering all of the visual chart objects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Components\VisualChart;

use vulkan\Components\VisualChart\CommonVisualParamsTrait;
use vulkan\Core\AspectsOrbs\AspectLink;
use vulkan\Core\AspectsOrbs\AspectManager;
use vulkan\Core\ChartSection;
use vulkan\Core\Event;
use vulkan\Helpers\Strings;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\Core\Svg\Svg;
use vulkan\Core\Chart;
use vulkan\Core\C;

class Size {
    public $width, $height;

    public function __construct($width, $height) {
        list($this->width, $this->height) = [$width, $height];
    }
}

class Rect {
    public $x1, $y1, $x2, $y2;

    public function __construct($x1, $y1, $x2, $y2) {
        list($this->x1, $this->y1, $this->x2, $this->y2) = [$x1, $y1, $x2, $y2];
    }
}

class VisualChart extends Chart
{

    use CommonVisualParamsTrait;

    const DEFAULT_CSS_CLASS = 'vul-chart';

    const DIRECTION_CLOCKWISE = 1;
    const DIRECTION_ANTICLOCKWISE = 0;

    const START_FROM_ARIES = 1;
    const START_FROM_ASCENDANT = 2;

    public $canvas;

    public $innerRadius;
    public $centerX;
    public $centerY;

    /* @var $sections \vulkan\Visual\Rect */

    public $direction;
    public $startPosition;

    public $rotationAngle;

    /* @var $globalHouseScale \vulkan\Visual\GlobalHouseSectionScale */
    public $globalHouseScale;

    public static $onCalculate;
    public static $onChartBuilt;

    public function __construct($params) {
        parent::__construct($params);
        $this->canvas = new Svg();
    }

    public function innerRadius($value) {
        $this->innerRadius = $value;
        return $this;
    }

    public function direction($mode) {
        $this->direction = $mode;

        if ($mode == self::DIRECTION_CLOCKWISE) {
            $this->direction = 1;
        } elseif ($mode == self::DIRECTION_ANTICLOCKWISE) {
            $this->direction = -1;
        }

        return $this;
    }

    public function startPosition($mode) {
        $this->startPosition = $mode;

        if ($mode == self::START_FROM_ARIES) {
            if ($this->direction == 1) {
                $this->rotationAngle = M_PI / 2;
            } else {
                $this->rotationAngle = -M_PI / 2;
            }
        } else {
            if ($this->direction == 1) {
                $this->rotationAngle = M_PI / 2 + $this->globalHouseScale->attachedSection->houses[0]->position;
            } else {
                $this->rotationAngle = - M_PI / 2 - $this->globalHouseScale->attachedSection->houses[0]->position;
            }
        }
        return $this;
    }

    public function addSection($section, $key) {
        parent::addSection($section, $key);
        foreach($section->scales as $scale) {
            $scale->canvas = $this->canvas;
        }
        foreach ($section->points as $point) {
            $point->chart = $this;
        }
        return $section;
    }

    public function getPointSections() {
        $arr = [];
        foreach ($this->sections as $key => $section) {
            if (count($section->points)) {
                $arr[$key] = $section;
            }
        }
        return $arr;
    }

    public function run($width = null, $height = null) {

        $width = $width ?? $this->width;
        $height = $height ?? $this->height;

        $this->calculate();

        $this->direction($this->direction)
            ->startPosition($this->startPosition);

        list($this->centerX, $this->centerY) = [$width / 2, $height / 2];

        $this->canvas->begin($this->key, ['width' => $width, 'height' => $height, 'cssClass' => $this->cssClass]);

        $this->canvas->beginGroup([self::DEFAULT_CSS_CLASS, $this->cssClass]);
        $this->drawInnerCircle();

        $this->drawSections();
        if ($this->aspectManager->active) {
            $this->aspectManager->calculateAspects();
            $this->drawAspects();
        }

        $this->writeCredits();

        $this->canvas->endGroup();

        call_user_func(self::$onChartBuilt ?? function() {}, $this);

        return $this->canvas->code;
    }

    public function calculate() {
        foreach ($this->sections as $section) {
            if (count($section->points) && ChartSection::$onCalculate) {
                call_user_func(ChartSection::$onCalculate, $section);
            }
            // autodetect global houses scale and init it as needed
            foreach ($section->scales as $scale) {
                if ($scale->attachedSection) {
                    $this->globalHouseScale = $scale;
                    $this->globalHouseScale->attachedSection = $this->sections[$scale->attachedSection];
                }
            }
        }
    }

    public function drawSections() {
        $radius = $this->innerRadius;
        $this->chartRadius = $this->getSectionsWidth() + $this->innerRadius;

        foreach ($this->sections as $section) {
            if ($section->active) {
                $radius += $section->width;
                $section->radius($radius);
                $section->draw();
            }
        }

    }

    public function drawAspects() {
        $this->canvas->beginGroup('vul-aspects');

        foreach ($this->aspectManager->aspectList as $link) {
            if (true) {
                $degree = $link->aspectData->angleDegree;
                $c1 = $this->radiusPoint($this->innerRadius, $link->point1->position);
                $c2 = $this->radiusPoint($this->innerRadius, $link->point2->position);
                $this->canvas->line($c1[0], $c1[1], $c2[0], $c2[1], 'vul-aspect-' . $degree,
                    '" data-angle="' . $degree .
                    '" data-id = "' . $link->point1->info->id . ',' . $link->point2->info->id . '"');
            }
        }

        $this->canvas->endGroup();
    }

    public function aspectManager($data) {
        $aspManager = $data['class'] ? new $data['class']() : new AspectManager();
        $aspManager->parent($this);
        Config::settings($aspManager, $data);
        $this->aspectManager = $aspManager;
        return $this;
    }

    public function getSectionsWidth() {
        $width = 0;
        foreach($this->sections as $section) {
            $width += $section->width;
        }
        return $width;
    }

    public function globalHouseScale($sectionKey) {
        $this->globalHouseScale->attachedSection($this->getSection($sectionKey));
        return $this;
    }

    public static function fromJSON($fileName) {
        return new VisualChart(json_decode(file_get_contents($fileName), 1));
    }

    public function drawInnerCircle() {
        $this->canvas->circle($this->centerX, $this->centerY, $this->innerRadius, 'vul-inner-circle');
    }

    public function radiusPoint($radius, $angle) {
        $angle = $this->rotationAngle - $angle * $this->direction;
        return [
            round($radius * sin($angle) + $this->centerX, 1),
            round($radius * cos($angle) + $this->centerY, 1)
        ];
    }

    public function radiusLine($radius1, $radius2, $angle) {
        $angle = $this->rotationAngle - $angle * $this->direction;
        $sin = sin($angle);
        $cos = cos($angle);
        return [
            round($radius1 * $sin + $this->centerX, 1),
            round($radius1 * $cos + $this->centerY, 1),
            round($radius2 * $sin + $this->centerX, 1),
            round($radius2 * $cos + $this->centerY, 1)
        ];
    }

    public function ring($radiusInner, $radiusOuter, $class = "", $params = "") {
        $out = [$this->centerX - $radiusOuter, $this->centerY, $this->centerX + $radiusOuter, $this->centerY];
        $in = [$this->centerX - $radiusInner, $this->centerY, $this->centerX + $radiusInner, $this->centerY];
        $this->canvas->code .= "
            <path d=\"M {$out[0]},{$out[1]} A20,20 0 0,1 {$out[2]},{$out[3]} " .
                "M {$out[0]},{$out[1]} A20,20 0 0,0 {$out[2]},{$out[3]} " .
                "M {$in[2]},{$in[3]} A 20,20 0 0,1 {$in[0]},{$in[1]} " .
                "M {$in[2]},{$in[3]} A 20,20 0 0,0 {$in[0]},{$in[1]}\" class=\"$class\" $params />";
    }

    public function drawCaption($section, $position, $text, $class = "") {
        $coord = $this->radiusPoint($section->innerRadius + $section->width / 2, $position);
        $this->canvas->text($coord[0], $coord[1] + 5, $text, $class);
    }

    public function writeCredits() {
        $this->canvas->text(20, 20, 'astrolog-online.net', 'vul-credits', 'font-size="12"');
    }

    public function toArray() {
        return array_merge(
            parent::toArray(),
            Transfer::serializeObjectVars($this,
                'key', 'centerX', 'centerY', 'innerRadius', 'direction', 'rotationAngle'),
            [
                'globalHouseScale' => $this->globalHouseScale->attachedSection->key
            ]
        );
    }


}