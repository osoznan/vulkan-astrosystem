<?php
/**
 * Class which manages aspects
 * Calculation of aspects itself, setting points for aspectation etc.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\AstroPoint;
use vulkan\Core\BaseAstroPoint;
use vulkan\System\C;
use vulkan\Core\Chart;
use vulkan\Core\InfoObjects\AspectInfo;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\System\Vulkan;

class AspectManager {

    const ORB_CALC_TYPE_AVERAGE = 0;
    const ORB_CALC_TYPE_MAX = 1;
    const ORB_CALC_TYPE_MIN = 2;

    const ASPECTATION_POINTS = 1;
    const ASPECTATION_HOUSES = 2;

    const MAX_ORBIS = 0.26;

    /** @var Chart Parent element */
    public $parent;

    /** @var AstroPoint[] The first point list to aspect */
    public $points1;
    /** @var AstroPoint[] The second point list to aspect */
    public $points2;

    /** @var AspectSet */
    public $aspectSet;

    /** @var Aspect[] List of found aspects */
    public $aspectList = [];

    public $active = true;

    public $orbCalcType = self::ORB_CALC_TYPE_AVERAGE;

    public static $onAspectedPointsChange;

    function __construct($params = null) {
        Config::settings($this, $params);
        if (!$this->aspectSet) {
            $aspSet = Vulkan::getConfig('aspectSet');
            $this->aspectSet(new $aspSet['class'](['name' => 'general']));
        }
    }

    public function parent($value) {
        $this->parent = $value;
        return $this;
    }

    public function getParent() {
        return $this->parent;
    }

    function aspectSet($data) {
        if (is_array($data)) {
            if (isset($data['class'])) {
                $this->aspectSet = new $data['class']();
            }
            Config::settings($this->aspectSet, $data);
        } else {
            $this->aspectSet = $data;
        }
        return $this;
    }

    /**
     * Sets the point lists which to calc aspects between
     * @param AstroPoint[] $points1
     * @param AstroPoint[] $points2
     * @return $this
     */
    function aspectedPoints($points1, $points2) {
        $this->points1 = $points1;
        $this->points2 = $points2;
        $this->aspectSet->orbIds(array_merge($this->points1, $this->points2));
        if (self::$onAspectedPointsChange) {
            call_user_func(self::$onAspectedPointsChange, [$points1, $points2]);
        }
        return $this;
    }

    /**
     * Setting the point list in a widely used manner: selecting sections
     * to aspect between and what to aspect (points or houses or both)
     * @param $params Array with two elements, each as ['key' => SectionKeyName, 'aspectation' => AspectManager::ASPECT_POINTS]
     * @return $this
     */
    function aspectedSections($params) {
        $this->aspectedPoints(
            array_merge(($params[0]['aspectation'] & self::ASPECTATION_POINTS) ? $this->parent->sections[$params[0]['key']]->points : [],
                ($params[0]['aspectation'] & self::ASPECTATION_HOUSES) ? $this->parent->sections[$params[0]['key']]->houses : []),
            array_merge(($params[1]['aspectation'] & self::ASPECTATION_POINTS) ? $this->parent->sections[$params[1]['key']]->points : [],
                ($params[1]['aspectation'] & self::ASPECTATION_HOUSES) ? $this->parent->sections[$params[1]['key']]->houses : [])
        );
        return $this;
    }

    /**
     * calculates aspect between the given points
     * @param AstroPoint $point1
     * @param AstroPoint $point2
     * @return Aspect
     */
    function calculateAspect($point1, $point2) {
        $distance = abs($point1->getPos() - $point2->getPos());
        $distance = min($distance, _2PI - $distance);
        $orbCalcType = $this->orbCalcType;

        foreach ($this->aspectSet->orbs as $aspectId => $orbs) {
            if ($this->aspectSet->aspectsActive[$aspectId]) {
                $aspect = C::$aspectInfos[$aspectId];
                // the if cascade is significantly faster than calling a closure for orb calc
                if ($orbCalcType == self::ORB_CALC_TYPE_AVERAGE) {
                    $orbis = ($orbs[$point1->orbId] + $orbs[$point2->orbId]) / 2 * $this->aspectSet->coef;
                } elseif ($orbCalcType = self::ORB_CALC_TYPE_MAX) {
                    $orbis = max($orbs[$point1->orbId], $orbs[$point2->orbId]) * $this->aspectSet->coef;
                } else {
                    $orbis = min($orbs[$point1->orbId], $orbs[$point2->orbId]) * $this->aspectSet->coef;
                }

                if ($orbis > self::MAX_ORBIS) {
                    $orbis = self::MAX_ORBIS;
                }

                if (($distance >= $aspect->angle - $orbis) && ($distance <= $aspect->angle + $orbis)) {
                    return new Aspect($point1, $point2, $aspect);
                }
            }
        }
    }

    /**
     * Calculates all aspects between the lists of points which are set with $this->aspectedPoints
     * @return Aspect[]
     * @throws \Exception
     */
    function calculateAspects() {

        if (!$this->points1 || !$this->points2) {
            return;
        }

        $result = [];
        // whether to calculate all combinations (if $points1 == $points2)
        $isCompleteCross = $this->points1 != $this->points2;

        foreach ($this->points1 as $point1) {
            if ($point1->aspected) {
                foreach ($this->points2 as $point2) {
                    if ($point2->aspected && $point1 !== $point2
                            && ($point1->info->id < $point2->info->id || $isCompleteCross)) {
                        $res = $this->calculateAspect($point1, $point2);
                        if ($res) {
                            $result[] = $res;
                        }
                    }
                }
            }
        }

        $this->aspectList = $result;
        return $result;
    }

    function toArray() {
        return array_merge(Transfer::serializeObjectVars($this, 'aspectList'),
                Transfer::serializeObjectVarsMinimized($this, 'points1', 'points2', 'aspectSet'));
    }

}