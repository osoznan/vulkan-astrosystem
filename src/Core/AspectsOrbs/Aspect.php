<?php
/**
 * Aspect as a result of calculation between two astropoints
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @package Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\C;
use vulkan\System\Transfer;

class Aspect {

    /* @var $info \vulkan\Core\AspectsOrbs\AspectData */
    public $aspectData;
    /* @var $point1 \vulkan\Core\AstroPoint */
    public $point1;
    /* @var $point2 \vulkan\Core\AstroPoint */
    public $point2;

    function __construct($point1, $point2, $aspectInfo = null) {
        $this->point1 = $point1;
        $this->point2 = $point2;
        $this->aspectData = $aspectInfo;
    }

    function distance() {
        return abs(modRad($this->point1->position) - modRad($this->point2->position));
    }

    function accuracy() {
        if (!$this->aspectData) {
            return null;
        }
        //$distance = radDeg(abs(modRad($aspect->point1->position - $aspect->point2->position))) . '|';
        $distance = $this->distance();
        return $this->aspectData->info->angle - min($distance, _2PI - $distance) . '||';
    }

    function isAspect() {
        return $this->aspectData != null;
    }

    function toArray() {
        return array_merge([
            'point1' => $this->point1->info->id,
            'point2' => $this->point2->info->id,
            'aspect' => $this->aspectData->info->id
        ]);
    }

}