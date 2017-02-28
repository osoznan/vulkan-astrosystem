<?php
/**
 * Class for a section, which holds one "ring" in a chart which has its date/time/gmt/lon/lat, points.
 * For instance, synastry chart has at least two sections, one for each human/event.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

// single horoscope section
namespace vulkan\Core;

use vulkan\VisualChart\VisualAstroPoint;
use vulkan\VisualChart\VisualHouse;
use vulkan\front\Controllers\Controller;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\System\Vulkan;
use vulkan\System\C;

class ChartSection {

    const POINT_SECTION = 1;
    const SYSTEM_SECTION = 2;

    /** @var Chart which owns the ChartSection */
    public $chart;
    /** @var string Unique key for a section */
    public $key;

    public static $type = self::POINT_SECTION;

    /** @var ChartSectionInfo ChartSectionInfo object (as usual), with necessary data for a section (date, time, gmt, location) */
    public $info;

    public $active = true;

    /** @var object Denotes info about a house system, it depends on method of ephemeris calc */
    public $houseSystem;

    /** @var AstroPoint[] Sky bodies or any points ("astropoints"): Sun, Moon, Planets, Nodes, Parses etc */
    public $points = [];

    /** @var House[] House cusps */
    public $houses = [];

    /** @var SectionScale[] Section scales */
    public $scales = [];

    /** @var EphemerisAdapter Object for ephemeris calculation */
    public static $ephemerisAdapter;

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
        if (!is_array($info)) {
            $this->info = $info;
        } else {
            $className = C::checkParam($info['class'], '\vulkan\Core\ChartSectionInfo');
            $this->info = new $className;
            Config::settings($this->info, $info);
        }
        return $this;
    }

    /**
     * Adds points.
     * @param BaseAstroPoint[] $points Points to add
     * @return $this
     */
    public function addPoints($points) {
        $this->points = array_merge($this->points, $points);
        $i=0;
        foreach ($points as $point) {
            $point->section($this);
        }
        return $this;
    }

    /**
     * Adds houses.
     * @param BaseAstroPoint[] $houses
     * @return $this
     */
    public function addHouses($houses) {
        $this->houses = array_merge($this->houses, $houses);
        $i=0;
        foreach ($houses as $house) {
            $house->section($this);
        }
        return $this;
    }

    /**
     * Adds a scale.
     * @param Scale object to add
     * @return $this
     */
    public function addScale($scale) {
        if (is_array($scale)) {
            Config::settings($scale = new $scale['class'], $scale);
        } else {
            $scale->chart = $this->chart;
            $scale->section = $this;
            $this->scales[] = $scale;
            return $this;
        }
    }

    /**
     * creates all available points for a section (exc. house cusps)
     * @param integer[] $pointIds
     * @return AstroPoint[]
     */
    public static function createPoints(...$pointIds) {
        $points = [];
        foreach ($pointIds as $id) {
            $points[] = new VisualAstroPoint(C::$pointInfos[$id]);
        }
        return $points;
    }

    /**
     * Creates all house cusps
     * @return House[]
     */
    public static function createHouses() {
        $houses = [];
        foreach (C::$houseInfos as $house) {
            $houses[] = new VisualHouse($house);
        }
        return $houses;
    }

    public function points($pointIds) {
        $this->addPoints(static::createPoints(...$pointIds));
        return $this;
    }

    public function houses() {
        $this->addHouses(\vulkan\Core\ChartSection::createHouses());
        return $this;
    }


    public function calculate() {
        if (self::$type == self::POINT_SECTION) {
            Vulkan::$ephemerisAdapter->calculate($this);
        }
    }

    /**
     * Calculates houses for all points. It's for further chart calculations, an optimization.
     * @param AstroPoint[] $points Points to calculate the houses for
     * @param House[] $houses A house cusps list, from which to calculate the house positions
     */
    public function calculateHousesOfPoints($points = null, $houses = null) {
        foreach (C::checkParam($points, $this->points) as $point) {
            $point->houseId = C::getHouse($point->position, C::checkParam($houses, $this->houses));
        }
    }

    public function calculateSignsOfPoints($points = null, $houses = null) {
        foreach (C::checkParam($points, $this->points) as $point) {
            $point->signId = $point->getSignId();
        }
    }


    /**
     * Calculates the house widths. It's for further chart calculations, an optimization.
     * @param House[] $houses A house cusps list, from which to calculate the house positions
     */
    function calculateWidthsOfHouses($houses = null) {
        $houses = C::checkParam($houses, $this->houses);
        $curHouse = reset($houses);
        $houses[11]->width = $houses[11]->distanceFrom($curHouse);
        while ($nextHouse = next($houses)) {
            current($houses)->width = $nextHouse->distanceFrom(current($houses));
        }
    }

    public function positions($positionParams) {
        $i = 0;
        foreach ($positionParams as $key => $posParam) {
            $point = $this->points[$key];
            if (!is_numeric($posParam[0])) {
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
            if ($position && $point) {
                $point->position = C::degRad($position);
            }
        }
        return $this;
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'key', 'info', 'description', 'points', 'houses', 'scales');
    }

}