<?php
/**
 * Base class which describes a chart.
 * This is a chart for calculations only, it cannot be drawn
 * Use the Chart descendant (vulkan\Components\VisualChart\VisualChart) for drawing charts
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\AspectsOrbs\AspectManager;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\System\Vulkan;

class Chart {

    /** @var object Parent element of a chart, it can be any object, i.e. list of charts or something else which holds the Chart */
    public $parent;
    /** @var string Used to distingish charts if needed */
    public $key;
    /** @var string Title of a chart, can be omitted */
    public $title;

    /** @var ChartSection[] Sections of a chart */
    public $sections = [];
    /** @var AspectManager */
    public $aspectManager;

    /** @var EphemerisAdapter Adapter which manages the ephemeris calculations */
    public static $ephemerisAdapter;

    public function __construct($params = null) {
        Config::settings($this, $params, 'chart');
        if (!$this->aspectManager) {
            $aspManager = Vulkan::getConfig('aspectManager.class');
            $this->aspectManager(new $aspManager);
        }
    }

    public function run() {
        $this->calculate();
        if ($this->aspectManager->active) {
            $this->aspectManager->calculateAspects();
        }
        return $this;
    }

    public function calculate() {
        foreach ($this->sections as $section) {
            $section->calculate();
        }
    }

    public function getParent() {
        return $this->parent;
    }

    /**
     * Gets a section with a given key in a safe way
     * @param string $key Section key
     * @return ChartSection
     */
    public function getSection($key) {
        if (isset($this->sections[$key])) {
            return $this->sections[$key];
        }
        return false;
    }

    /**
     * Sets the sections
     * @param ChartSection[] Array with params of sections
     * @return Chart $this
     */
    public function sections($sections) {
        foreach ($sections as $section) {
            if (is_array($section)) {
                if (isset($section['class'])) {
                    $sect = new $section['class']();
                } else {
                    $defaultSectClass = Vulkan::getConfig('chartSection.class');
                    Config::settings($sect = new $defaultSectClass, $section);
                }
                Config::settings($sect, $section);
                if ($sect->key) {
                    $this->addSection($sect, $sect->key);
                } else {
                    throw new \Exception(_('Wrong or duplicate section key'));
                }
            } else {
                $this->addSection($section, $section->key);
            }
        }
        return $this;
    }

    /**
     * Adds a section with a given key
     * @param ChartSection $section
     * @param string $key Section key
     * @return $this
     */
    public function addSection($section, $key = null) {
        if (!is_array($section)) {
            if ($key && !isset($this->sections[$key])) {
                $section->chart($this);
                $section->key = $key;
            } else {
                throw new \Exception(_('Wrong or duplicate section key'));
            }
        } else {
            Config::settings(new $section['class'], $section);
        }
        $this->sections[$key] = $section;
        foreach (array_merge($section->points, $section->houses) as $point) {
            $point->chart = $this;
        }
        foreach ($section->scales as $scale) {
            $scale->chart = $this;
        }
        return $this;
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

    /**
     * Sets the AspectManager object
     * @param array|object $data Params
     * @return $this
     */
    public function aspectManager($data) {
        if (is_array($data)) {
            if (isset($data['class'])){
                $aspManager = new $data['class'];
            } else {
                $aspManDefault = Vulkan::getConfig('aspectManager.class');
                $aspManager = new $aspManDefault;
            }
            $aspManager->parent($this);
            Config::settings($aspManager, $data);
            $this->aspectManager = $aspManager;
        } else {
            $this->aspectManager = $data;
            $this->aspectManager->parent($this);
        }
        return $this;
    }

    /**
     * Sets two arrays of points and calculates aspects between them if needed
     * @param array $points1 The first point list to aspect
     * @param array $points2 The second point list to aspect
     * @param bool $isCalcAspects Is calculate aspects
     * @return $this
     */
    public function aspectedPoints($points1, $points2, $isCalcAspects = false) {
        $this->aspectManager->aspectedPoints($points1, $points2);
        if ($isCalcAspects) {
            $this->aspectManager->calculateAspects();
        }
        return $this;
    }

    public function toArray() {
        return array_merge(
            Transfer::serializeObjectVars($this, 'key', 'aspectManager', 'sections')
        );
    }

}