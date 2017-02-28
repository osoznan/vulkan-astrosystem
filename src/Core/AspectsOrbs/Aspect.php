<?php
/**
 * Aspect as a result of calculation between two astropoints
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\System\C;
use vulkan\System\Transfer;

class Aspect {

    /* @var $info \vulkan\Core\InfoObjects\AspectInfo */
    public $info;
    /* @var $point1 \vulkan\Core\AstroPoint */
    public $point1;
    /* @var $point2 \vulkan\Core\AstroPoint */
    public $point2;

    function __construct($point1, $point2, $aspectInfo = null) {
        $this->point1 = $point1;
        $this->point2 = $point2;
        $this->info = $aspectInfo;
    }

    /**
     * Distance between the aspect's points
     * @return number
     */
    function distance() {
        $distance = abs(C::modRad($this->point1->position) - C::modRad($this->point2->position));
        return min($distance, _2PI - $distance);
    }

    /**
     * Accuracy (distance from the exact aspect angle and the aspect between the aspect's points)
     * @return null|number
     */
    function accuracy() {
        if (!$this->info) {
            return null;
        }
        $distance = $this->distance();
        return abs($this->info->angle - min($distance, _2PI - $distance));
    }

    function isAspect() {
        return $this->info != null;
    }

    function toArray() {
        return array_merge([
            'point1' => $this->point1->info->id,
            'point2' => $this->point2->info->id,
            'aspect' => $this->info->id
        ]);
    }

}