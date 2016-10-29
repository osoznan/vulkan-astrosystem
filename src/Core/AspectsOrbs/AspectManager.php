<?php
/**
 * Class which manages aspects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @package Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Analytics\PointsCalc;
use vulkan\Core\C;
use vulkan\Core\Event;
use vulkan\Core\EventData;
use vulkan\Core\InfoObjects\AspectInfo;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\Core\Vulkan;

class AspectManager {

    const ORB_CALC_TYPE_AVERAGE = 0;
    const ORB_CALC_TYPE_MAX = 1;
    const ORB_CALC_TYPE_MIN = 2;

    const MAX_ORBIS = 0.26;

    public $parent;

    public $active = true;

    public $points1;
    public $points2;

    public $aspectSet;

    public $aspectList = [];

    public $orbCalcType = self::ORB_CALC_TYPE_AVERAGE;

    function __construct($params = null) {
        Config::settings($this, $params);
    }

    public function parent($value) {
        $this->parent = $value;
        return $this;
    }

    public function getParent() {
        return $this->parent;
    }

    function aspectedPoints($points1, $points2) {
        $this->points1 = $points1;
        $this->points2 = $points2;
        $this->aspectSet->orbIds($this->points1);
        $this->aspectSet->orbIds($this->points2);
        return $this;
    }

    function aspectedSections($data) {
        $points1 = $this->parent->getSection($data['keys'][0])->points;
        $points2 = $this->parent->getSection($data['keys'][1])->points;
        $this->aspectedPoints($points1, $points2);
        return $this;
    }

    function calculateAspect($point1, $point2) {
        $distance = abs($point1->getPos() - $point2->getPos());
        $distance = min($distance, _2PI - $distance);
        $orbCalcType = $this->orbCalcType;

        foreach ($this->aspectSet->orbs as $aspectId => $orbs) {
            //if ($this->aspectSet->aspectsActive[$aspectId]) {
                $aspect = C::$aspectInfos[$aspectId];
                // the if cascade is significantly faster than calling a closure for orb calc
                if ($orbCalcType == self::ORB_CALC_TYPE_AVERAGE) {
                    $orbis = ($orbs[$point1->orbId] + $orbs[$point2->orbId]) / 2 * $this->aspectSet->coef;
                } elseif ($orbCalcType = self::ORB_CALC_TYPE_MAX) {
                    $orbis = max($orbs[$point1->orbId], $orbs[$point2->orbId]) * $this->aspectSet->coef;
                } else {
                    $orbis = min($orbs[$point1->orbId], $orbs[$point2->orbId]) * $this->aspectSet->coef;
                }
               /* if ($orbis > self::MAX_ORBIS) {
                    $orbis = self::MAX_ORBIS;
                }*/
                if (($distance >= $aspect->angle - $orbis) && ($distance <= $aspect->angle + $orbis)) {
                    return new Aspect($point1, $point2, $aspect);
                }
            //}
        }
    }

    function calculateAspects() {
        if (!$this->points1 || !$this->points2) {
            return;
        }

        $result = [];
        foreach ($this->points1 as $point1) {
            if ($point1->aspected && $point1->visible) {
                foreach ($this->points2 as $point2) {
                    if ($point2->aspected && $point2->visible &&
                        $point1 !== $point2 && $point1->info->id < $point2->info->id
                    ) {
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

    function aspectFromDegree($degree) {
        //var_dump( $this->aspectSet->data[AspectInfo::aliasToId(intval($degree))] ); die();
        return $this->aspectSet->aspectFromDegree(AspectInfo::aliasToId(intval($degree)));
    }

    function toArray() {
        return array_merge(Transfer::serializeObjectVars($this, 'aspectList'),
                Transfer::serializeObjectVarsMinimized($this, 'points1', 'points2', 'aspectSet'));
    }

}