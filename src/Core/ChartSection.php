<?php
/**
 * Class for a section, which holds one "ring" in a chart which has its date/time/gmt/lon/lat, points.
 * For instance, synastry chart has at least two sections, one for each human/event.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

// single horoscope section
namespace vulkan\Core;

use vulkan\Components\VisualChart\VisualAstroPoint;
use vulkan\Components\VisualChart\VisualHouse;
use vulkan\System\Config;
use vulkan\System\Transfer;
use yii\base\Exception;

class ChartSection {

    const POINT_SECTION = 1;
    const SYSTEM_SECTION = 2;

    public $chart;
    public $key;

    public static $type = self::POINT_SECTION;

    public $info;

    public $active = true;

    public $houseSystem;

    // as usual for Sun, Moon, Planets, Nodes
    public $points = [];

    // house cusps
    public $houses = [];

    public $scales = [];

    public static $onCalculate;

    public function __construct($params = null) {
        Config::settings($this, $params);
    }

    public function getChart() {
        return $this->chart;
    }

    public function getParent() {
        return $this->chart;
    }

    public function chart($value) {
        $this->chart = $value;
        return $this;
    }

    public function info($info) {
        $this->info = new $info['class'];
        Config::settings($this->info, $info);
    }

    public function addPoints($points) {
        $this->points = array_merge($this->points, $points);
        $i=0;
        foreach ($points as $point) {
            $point->section($this);
        }
        return $this;
    }

    public function addHouses($houses) {
        $this->houses = array_merge($this->houses, $houses);
        $i=0;
        foreach ($houses as $house) {
            $house->section($this);
        }
        return $this;
    }

    public function addScale($scale) {
        $scale->chart = $this->chart;
        $scale->section = $this;
        $this->scales[] = $scale;
        return $this;
    }

    // create all available points for a section (exc. house cusps)
    public static function createPoints(...$pointIds) {
        $points = [];
        foreach ($pointIds as $id) {
            $points[] = new VisualAstroPoint(C::$pointInfos[$id]);
        }
        return $points;
    }

    // create all house cusps
    public static function createAllHouses() {
        $houses = [];
        foreach (C::$houseInfos as $house) {
            $houses[] = new VisualHouse($house);
        }
        return $houses;
    }

    function calculateHousesOfPoints($houses = null) {
        $positions = [];
        foreach ($houses ?? $this->houses as $house) {
            $positions[] = $house->position;
        };

        foreach ($this->points as $point) {
            $point->houseId = getHouse($point->position, $positions);
        }
    }

    public function positions($positionParams) {
        $i = 0;
        foreach ($positionParams as $posParam) {
            $point = $this->points[$i++];
            if (!is_numeric($posParam)) {
                $position = substr($posParam, 1);

                switch ($posParam[0]) {
                    case 'R':
                        $point->speed = -1;
                        break;
                    case '-':
                        $point->visible = false;
                        break;
                    case '!':
                        $point->selected = true;
                        break;
                    case '*':
                        $position = rand(1, 36000)/100;
                }
            } else {
                $position = $posParam;
            }

            $point->position = DegRad($position);

        }
        return $this;
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'key', 'info', 'desription', 'points', 'houses', 'scales');
    }

}